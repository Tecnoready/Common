<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\Breadcrumb;

use stdClass;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Servicio para construir Breadcrumb
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class BreadcrumbManager {
    
    private $options;
    private $breadcrumbs;
    private $mainIcon = null;
    /**
     *
     * @var \Twig_Environment
     */
    private $twig;
    
    public function __construct(\Twig_Environment $twig, array $options = array()) {
        $this->twig = $twig;
        
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
           "twig_breadcrumb_template" => null,
           "prefix_icon" => null,
           "main_icon" => null,
        ]);
        $resolver->setRequired("twig_breadcrumb_template");
        $resolver->setAllowedTypes("twig_breadcrumb_template","string");
        $this->options = $resolver->resolve($options);
        $this->mainIcon = $this->options["main_icon"];
    }
    
    public function breadcrumb()
    {
        $parameters = array();
        $args = func_get_args();
        foreach ($args as $key => $arg) {
            if(empty($arg)){
                continue;
            }
            $item = new stdClass();
            $item->link = null;
            $item->label = null;
            if(is_array($arg)){
                $count = count($arg);
                if($count > 1){
                    foreach ($arg as $key => $value) {
                        $link = $key;
                        if(is_int($key)){
                            $link = null;
                        }
                        $item = new stdClass();
                        $item->link = $link;
                        $item->label = $value;
                        $parameters[] = $item;
                    }
                }else{
                    foreach ($arg as $key => $value) {
                        $link = $key;
                        if(is_int($key)){
                            $link = null;
                        }
                        $item->link = $link;
                        $item->label = $value;
                        $parameters[] = $item;
                    }
                }
            }else{
                $item->label = $arg;
                $parameters[] = $item;
            }
            
        }
        
        if($this->breadcrumbs === null){
            $this->breadcrumbs = [];
        }
        $this->breadcrumbs = array_merge($this->breadcrumbs, $parameters);
        
        return $this;
    }
    
    public function breadcrumbRender(){
        $template = $this->options["twig_breadcrumb_template"];
        $breadcrumbs = $this->breadcrumbs;
        $mainIcon = null;
        if($this->mainIcon){
            $mainIcon = $this->options["prefix_icon"]." ".$this->mainIcon;
        }
        $this->breadcrumbs = [];
        $this->mainIcon = null;
        return $this->twig->render($template, 
            array(
                'breadcrumbs' => $breadcrumbs,
                'main_icon' => $mainIcon,
            )
        );
    }
    
    public function setMainIcon($mainIcon) {
        $this->mainIcon = $mainIcon;
        return $this;
    }
}
