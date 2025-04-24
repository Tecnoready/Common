<?php

namespace Tecnoready\Common\Service;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Psr\Log\LoggerInterface;
use Monolog\Logger as MonoLogger;

/**
 * Manejador de logs
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class LogsManager {

    private $loggers = [];

    /**
     * Opciones de configuracion
     * @var array
     */
    private $options = [];
    
    /**
     * Logger base para extraer monolog
     * @var MonoLogger
     */
    private $baseLogger;
    
    /**
     * Procesadores
     * @var Array
     */
    private $processors;

    public function __construct(LoggerInterface $logger,array $options = []) {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "max-days" => 31,
            "log-level" => Logger::DEBUG, //App::environment('production') === true ? Logger::DEBUG : Logger::DEBUG
            "user-resolver" => null,
        ]);
        $resolver->setRequired(["root-path"]);
        $this->options = $resolver->resolve($options);
        
        $this->baseLogger = $logger;
        $this->processors = [];
    }

    /**
     * Genera un logger personalizado a un archivo y carpeta especifico segun categoria
     * @param type $name
     * @param array $options
     * @return Logger
     */
    public function getLogger($name, array $options = array()) {
        if (isset($this->loggers[$name])) {
            return $this->loggers[$name];
        }
        $resolver = new OptionsResolver();
        $resolver->setDefined(["name"]);
        $resolver->setDefaults([
            "category" => "default",
            "log-level" => $this->options["log-level"],
        ]);

        $options = $resolver->resolve($options);

        $fullPath = sprintf('%s/lm/%s/%s/%s.log', $this->options["root-path"], $options["category"], $name, $name);
        $log = new Logger($name);
        $rotatingFileHandler = new RotatingFileHandler($fullPath, $this->options["max-days"], $options["log-level"]);

        //Seteamos base para replicar comportamiento
        if($this->baseLogger instanceof MonoLogger){
            $monolog = $this->baseLogger;
            foreach ($monolog->getHandlers() as $handler) {
                if ($handler instanceof RotatingFileHandler) {
                   /** @var RotatingFileHandler $originalRotatingFileHandler */
                   $originalRotatingFileHandler = $handler;
                   $rotatingFileHandler->setFormatter($originalRotatingFileHandler->getFormatter());
                   continue;
                }
                $log->pushHandler($handler);
            }
        }
        if(count($this->processors) > 0){
            foreach ($this->processors as $processor) {
                $rotatingFileHandler->pushProcessor($processor);
            }
        }else{
            foreach ($this->getDefaultProcessors() as $processor) {
                $rotatingFileHandler->pushProcessor($processor);
            }
        }
        $log->pushHandler($rotatingFileHandler);

        return $log;
    }

    /**
     * Genera un logger con el nombre de un objeto
     * @param type $object
     * @param array $options
     * @return Logger
     */
    public function getFromObject($object, array $options = array()) {
        $className = "";
        $name = null;
        if(is_object($object)){
            $className = get_class($object);
        }else{
            $className = $object;
        }
        $strrCh = strrchr($className, '\\');
        if($strrCh !== false){
            $className = $strrCh;
            $name = substr($className, 1);
        }
            
        if(empty($name)){
            $name = $className;
        }
        
        
        return $this->getLogger($name, $options);
    }
    
    /**
     * Procesadores por defecto
     * @return array
     */
    private function getDefaultProcessors() {
        return  [
            new \Monolog\Processor\WebProcessor(null, [
                        'url' => 'REQUEST_URI',
                        'ip' => 'REMOTE_ADDR',
                        'http_method' => 'REQUEST_METHOD',
                        'server' => 'SERVER_NAME',
                        'referrer' => 'HTTP_REFERER',
                        'user_agent' => 'HTTP_USER_AGENT',
                        'server_addr'    => 'SERVER_ADDR',
            ]),
            new \Monolog\Processor\MemoryUsageProcessor(),
            new \Monolog\Processor\HostnameProcessor(),
        ];
    }


    /**
     * Añadir procesadores base
     * @param type $processor
     * @return $this
     */
    public function addProcessor($processor) {
        $this->processors[] = $processor;
        return $this;
    }
}
