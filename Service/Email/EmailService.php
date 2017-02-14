<?php

/*
 * This file is part of the BtoB4Rewards package.
 * 
 * (c) www.btob4rewards.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Service\Email;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ODM\PHPCR\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\PHPCR\Configuration;
use Doctrine\ODM\PHPCR\DocumentManager;
use Tecnoready\Common\Util\ConfigurationUtil;

/**
 * Servicio para renderizar y enviar correos a partir de la base de datos con doctrine
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class EmailService 
{
    private $options;
    
    /**
     * Manejador de documentos
     * @var DocumentManager
     */
    private $documentManager;


    public function __construct(array $params,array $options) {
        ConfigurationUtil::checkLib("optionsResolver");
        
        $params = array(
            'driver'    => 'pdo_pgsql',
            'host'      => 'localhost',
            'user'      => 'postgres',
            'password'  => '184118cemb',
            'dbname'    => 'btobrewards',
        );
        
        $workspace = 'default';
        $user = 'admin';
        $pass = 'admin';
        
//        $this->setOptions($options);
        
        /* --- transport implementation specific code begin --- */
        // for more options, see https://github.com/jackalope/jackalope-doctrine-dbal#bootstrapping
        $dbConn = \Doctrine\DBAL\DriverManager::getConnection($params);
        $parameters = array('jackalope.doctrine_dbal_connection' => $dbConn);
        $dbal = new \Jackalope\RepositoryFactoryDoctrineDBAL();
        $repository = $dbal->getRepository($parameters);
        $credentials = new \PHPCR\SimpleCredentials(null, null);
        /* --- transport implementation specific code  ends --- */
        $session = $repository->login($credentials, $workspace);
//        AnnotationRegistry::registerLoader(array($autoload, 'loadClass'));

        $reader = new AnnotationReader();
        $driver = new AnnotationDriver($reader, array(
            // this is a list of all folders containing document classes
            'vendor/doctrine/phpcr-odm/lib/Doctrine/ODM/PHPCR/Document',
            'vendor/tecnoready/common/Document',
            //'src/Demo',
        ));
        
        $localePrefs = array(
            'en' => array('es'),
            'es' => array('en'),
        );
        
        $config = new Configuration();
        $config->setMetadataDriverImpl($driver);

        $documentManager = DocumentManager::create($session, $config);
        $documentManager->setLocaleChooserStrategy(new \Doctrine\ODM\PHPCR\Translation\LocaleChooser\LocaleChooser($localePrefs, 'es'));
        
        $this->documentManager = $documentManager;
    }
    
    public function setOptions(array $options)
    {
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults([
            'date_format' => 'Y-m-d H:i:s',
        ]);
        
        $resolver->setRequired(["current_ip","date_format"]);
        $resolver->addAllowedTypes("current_ip","string");
        $resolver->addAllowedTypes("date_format","string");
        
        $this->options = $resolver->resolve($options);
    }
    
    public function getDocumentManager() {
        return $this->documentManager;
    }
}
