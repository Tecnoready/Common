<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tecnoready\Common\Dumper\Configuration;

use DateTime;
use Tecnoready\Common\Util\ConfigurationUtil;

/**
 * Genera la cache de la configuracion
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
class PhpConfigurationDumper 
{
    private $configurations;
    
    private $data;

    public function __construct(array $configurations) {
        $this->configurations = $configurations;
        $this->data = [];
    }
    
    public function dump(array $options = array()) {
        $now = new DateTime();
        $dumpedAt = $now->format("Y-m-d H:i:s");
        $options = array_replace(array(
            'class'      => 'ProjectConfigurationAvailable',
            'base_class'      => 'Tecnocreaciones\\Bundle\\ToolsBundle\\Model\\Configuration\\ConfigurationAvailable',
        ), $options);

        return <<<EOF
<?php

/**
 * {$options['class']}
 *
 * This class has been auto-generated
 * by the Tecnoready Tools Component.
 * dumped at {$dumpedAt}
 */
class {$options['class']} extends {$options['base_class']}
{
{$this->generateConfiguration()}
}

EOF;
    }
    
    private function generateConfiguration()
    {
        $code = rtrim($this->compileConfiguration());

        return <<<EOF

    protected \$configurations = array(
        $code
    );
EOF;
    }
    
     /**
     * Generates PHP code 
     *
     * @return string PHP code
     */
    private function compileConfiguration()
    {
        $code = '';
        
        foreach ($this->configurations as $key => $configuration) {
            $data = array();
            //$data['key'] = $configuration->getKey();
            $data['value'] = $configuration->getValue();
            //$data['enabled'] = $configuration->getEnabled();
//            $data['createdAt'] = $configuration->getCreatedAt();
//            $data['updatedAt'] = $configuration->getUpdatedAt();
            //$data['id'] = $configuration->getId();
            
            $id = ConfigurationUtil::generateId($configuration->getNameWrapper(),$configuration->getKey());
            $code .= sprintf("'%s' => %s,", $id ,var_export($data,true));
            $this->data[$id] = $data;
        }

        return $code;
    }
    
    public function getData() {
        return $this->data;
    }
}
