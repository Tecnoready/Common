<?php

namespace Tecnoready\Common\Service\Template\Engine;

use Tecnoready\Common\Model\Template\TemplateInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptador para exportar a txt para construir con echo
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TXTEchoEngine extends BaseEngine
{
    const NAME = "TXT_ECHO_NATIVE";

    /**
     * Opciones de configuracion
     * @var array
     */
    private $options;

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $tmpDir = sys_get_temp_dir();
        $resolver->setDefaults([
            "tmpDir" => $tmpDir, //Carpeta temporal
        ]);
        $this->options = $resolver->resolve($options);
    }

    public function render(TemplateInterface $template, array $variables)
    {
        $execute = function($variables, $templateContent) {
            $templateContent = str_replace("<?php","",$templateContent);
            ob_start();
            extract($variables);
            eval($templateContent);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        };
        $resul = $execute($variables, $template->getContent());
        unset($execute);

        return $resul;
    }

    public function compile($filename, $string, array $parameters)
    {
        if(file_exists($filename)){
            unlink($filename);
        }
        $fh = fopen($filename, "w");
        fwrite($fh,$string);
        fclose($fh);
        return file_exists($filename) === true;
    }

    public function getDefaultParameters()
    {
        return [];
    }

    public function getExtension()
    {
        return "TXT";
    }

    public function checkAvailability(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return "[PHP] Native (echo)";
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getExample(): string
    {
        $content = <<<EOF
<?php
echo "Hola \$name.\n";
echo "Apellido \$lastname.";
EOF;
        return $content;
    }

}
