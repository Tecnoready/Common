<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\Block;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Tecnoready\Common\Service\Block\WidgetManager;

/**
 * Handles the execution and rendering of a block.
 *
 * This function render a block and make sure the cacheable information are correctly retrieved
 * and set to the upper response (container can have child blocks, so the smallest ttl from a child
 * must be used in the container).
 */
class WidgetRenderer implements WidgetRendererInterface
{
    /**
     * @var WidgetManager
     */
    protected $widgetManager;

    /**
     * @var LoggerInterface|null
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * This property hold the last response available from the child or sibling block
     * The cacheable attributes must be cascaded to the parent.
     *
     * @var Response|null
     */
    private $lastResponse;

    /**
     * @param WidgetManager $widgetManager      Block service manager
     * @param StrategyManagerInterface     $exceptionStrategyManager Exception strategy manager
     * @param LoggerInterface              $logger                   Logger class
     * @param bool                         $debug                    Whether in debug mode or not
     */
    public function __construct(WidgetManager $widgetManager, LoggerInterface $logger = null, $debug = false)
    {
        $this->widgetManager = $widgetManager;
        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function render(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $blockContext->getBlock();

        if ($this->logger) {
            $this->logger->info(sprintf('[cms::renderBlock] block.id=%d, block.type=%s ', $block->getId(), $block->getType()));
        }

        try {
            $service = $this->widgetManager->getBlockService($block);

            $response = $service->execute($blockContext, $this->createResponse($blockContext, $response));

            if (!$response instanceof Response) {
                $response = null;

                throw new \RuntimeException('A block service must return a Response object');
            }

            $response = $this->addMetaInformation($response, $blockContext, $service);
        } catch (\Exception $exception) {
            if ($this->logger) {
                $this->logger->error(sprintf(
                    '[cms::renderBlock] block.id=%d - error while rendering block - %s',
                    $block->getId(),
                    $exception->getMessage()
                ), compact('exception'));
            }

            // reseting the state object
            $this->lastResponse = null;

            throw $exception;
        }

        return $response;
    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response              $response
     *
     * @return Response
     */
    protected function createResponse(BlockContextInterface $blockContext, Response $response = null)
    {
        if (null === $response) {
            $response = new Response();
        }

        // set the ttl from the block instance, this can be changed by the BlockService
        if (($ttl = $blockContext->getBlock()->getTtl()) > 0) {
            $response->setTtl($ttl);
        }

        return $response;
    }

    /**
     * This method is responsible to cascade ttl to the parent block.
     *
     * @param Response              $response
     * @param BlockContextInterface $blockContext
     * @param BlockServiceInterface $service
     *
     * @return Response
     */
    protected function addMetaInformation(Response $response, BlockContextInterface $blockContext, BlockServiceInterface $service)
    {
        // a response exists, use it
        if ($this->lastResponse && $this->lastResponse->isCacheable()) {
            $response->setTtl($this->lastResponse->getTtl());
            $response->setPublic();
        } elseif ($this->lastResponse) { // not cacheable
            $response->setPrivate();
            $response->setTtl(0);
            $response->headers->removeCacheControlDirective('s-maxage');
            $response->headers->removeCacheControlDirective('maxage');
        }

        // no more children available in the stack, reseting the state object
        if (!$blockContext->getBlock()->hasParent()) {
            $this->lastResponse = null;
        } else { // contains a parent so storing the response
            $this->lastResponse = $response;
        }

        return $response;
    }
}
