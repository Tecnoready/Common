<?php

namespace Tecnoready\Common\Service\Template\Engine;

use Tecnoready\Common\Model\Template\TemplateInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Adaptador para exportar a txt
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class TXTNativeEngine extends BaseEngine
{
    const NAME = "TXT_NATIVE";

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
            $tmpFile = tempnam(null, "txt");
            ob_start();
            $fh = fopen($tmpFile, "w");
            extract($variables);
            eval($templateContent);
            fclose($fh);
            ob_end_clean();
            $content = file_get_contents($tmpFile);
            unlink($tmpFile);
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
        return TemplateInterface::TYPE_TXT;
    }

    public function checkAvailability(): bool
    {
        return true;
    }

    public function getDescription(): string
    {
        return "[PHP] Native (fopen)";
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getExample(): string
    {
        $content = <<<EOF
<?php
fwrite(\$fh,"Hola \$name.\n");
fwrite(\$fh,"Apellido \$lastname.");
EOF;
        return $content;
    }

}
