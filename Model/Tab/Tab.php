<?php

namespace Tecnoready\Common\Model\Tab;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use RuntimeException;

/**
 * Tab para construir fichas
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class Tab
{
    const NAME_CURRENT_TAB = "_tabst82a";
    const LAST_CURRENT_TABS = "_tabs471";
    const SORT_PROPERTY = "_f3fe5b35e8ec8";
    const SORT_ORDER = "_e7c860cda";
    
    private $id;
    private $name;
    private $icon;    
    private $options;
    
    /**
     * Url base para todas las tabs content que no tengan url
     * @var string
     */
    private $rootUrl;
    
    /**
     * @var Request
     */
    private $request;
    
    /**
     * @var TabContent
     */
    private $tabsContent;
    
    /**
     * @var TabContent
     */
    private $currentTabContent;
    
    /**
     * Parametros de la tab
     * @var array 
     */
    private $parameters = [];

    public function __construct(array $options = []) 
    {
        $this->tabsContent = [];
        $this->id = null;
        $this->setOptions($options);
    }
    
    /**
     * Carga de opciones
     * @param  array
     */
    public function setOptions(array $options = []) 
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "active_first" => true,
            "object_id" => null,
        ]);
        $this->options = $resolver->resolve($options);
        
        return $this;
    }
    
    /**
     * getName
     * @return name
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * getIcon
     * @return icon
     */
    public function getIcon() 
    {
        return $this->icon;
    }

    /**
     * getTabsContent
     * @return tabsContent
     */
    public function getTabsContent() 
    {
        $this->resolveCurrentTab();
        return $this->tabsContent;
    }

    /**
     * setName
     * @param  name
     */
    public function setName($name) 
    {
        $this->name = $name;
        return $this;
    }

    /**
     * setIcon
     * @param  icon
     */
    public function setIcon($icon) 
    {
        $this->icon = $icon;
        return $this;
    }
    
    /**
     * getId
     * @return id
     */
    public function getId() 
    {
        if ($this->id === null) {
            $id = "";
            foreach ($this->tabsContent as $tabContent) {
                $id .= $tabContent->getId();
            }
            $this->id = "tab_" .md5($id."_".$this->options["object_id"]);
        }
        return $this->id;
    }
    
    /**
     * Añade una tab
     * @param \App\Model\Core\Tab\TabContent $tabContent
     * @return \App\Model\Core\Tab\Tab
     * @throws \RuntimeException
     */
    public function addTabContent(TabContent $tabContent) 
    {
        $id = "tc_".md5($tabContent->getTitle());
        if (isset($this->tabsContent[$id])) {
            throw new \RuntimeException(sprintf("The tab content name '%s' is already added.", $tabContent->getTitle()));
        }
        $this->tabsContent[$id] = $tabContent;
        $tabContent->setId($id);
        $tabContent->setTabRoot($this);

        return $this;
    }
    /**
     * @return TabContent
     */
    public function resolveCurrentTab() {
        if($this->currentTabContent){
            return $this->currentTabContent;
        }
        $current = null;
        $request = $this->request;
        $isNavitaging = false;
        if($request->getSession()->has(Tab::NAME_CURRENT_TAB)){
            $current = $request->getSession()->get(Tab::NAME_CURRENT_TAB);
        }
        
        $currentSession = $current;
        if($request->query->has(Tab::NAME_CURRENT_TAB)){//El valor del request remplaza el de la cache
            $current = $request->query->get(Tab::NAME_CURRENT_TAB);
            $request->getSession()->set(Tab::NAME_CURRENT_TAB,$current);
        }
//        var_dump("request ".$current);
        $lasts = $request->getSession()->get(self::LAST_CURRENT_TABS,[]);
//        $request->getSession()->remove(self::LAST_CURRENT_TABS);
        $id = $this->getId();
        //Si el id de la tab actual es el mismo significa que esta navegando
        $currentId = null;
        $currentTabId = null;
        if(!empty($currentSession)){
            $currentSessionExp = explode("#",$currentSession);
            if($currentSessionExp[0] !== $id){
                $isNavitaging = true;
                    foreach ($lasts as $key => $value) {//Buscar el ultimo historial
                        if(strpos($value, $id) !== false){//Tiene historial
                            //Si el nuevo movimiento es diferente
                            $current = $value;
                            break;
                        }
                    }
            }
        }
        if($request->get("isInit") === "1" && $request->query->has(Tab::NAME_CURRENT_TAB)){//Si ya la tab se inicializo, entonces se renderiza lo que venga en el request
            $current = $request->query->get(Tab::NAME_CURRENT_TAB);
        }
        $exp = explode("#", $current);
        if (count($exp) == 2) {
            $currentId = $exp[0];
            $currentTabId = $exp[1];
        }
        
//        var_dump("has ".$request->query->has(Tab::NAME_CURRENT_TAB));
//        var_dump("actial ".$id);
//        var_dump("cache current ".$current);
//        var_dump($lasts);
//        var_dump( array_values($lasts));
//        die;
        
        $activeTab = null;
        if (!empty($current)) {
//            if ($id === $currentId) {
                foreach ($this->tabsContent as $tabContent) {
                    if ($tabContent->getId() === $currentTabId) {
                        $activeTab = $tabContent;
                        break;
                    }
                }
//                die("fffff");
//            }
            //Guardar en las ultimas 10 navegaciones
            if($activeTab !== null){
                foreach ($lasts as $key => $value) {
                    if(strpos($value, $id) !== false){//Eliminar repetidos
                        unset($lasts[$key]);
                    }
                }
                array_unshift($lasts,$id."#".$activeTab->getId());
                $lasts = array_values($lasts);
                while(count($lasts) > 10){//Solo guardar los ultimos 10 historiales
                    unset($lasts[count($lasts) - 1]);
                }
                $request->getSession()->set(self::LAST_CURRENT_TABS,$lasts);
            }
            
        }
//        var_dump($activeTab->getTitle());
//        var_dump($activeTab);
//        die;
        if ($activeTab === null) {
            $activeTab = reset($this->tabsContent);
        }
        if($this->tabsContent !== null){
            $activeTab->setActive(true);
        }
        $this->currentTabContent = $activeTab;
        return $activeTab;
    }
    
    public function getTemplate($default)
    {
        $template = $default;
        if($this->request->isXmlHttpRequest()){
            $current = $this->resolveCurrentTab();
            if($current !== null){
                $template = $current->getOption("template");
            }
        }
        return $template;
    }
    
    /**
     * Convierte la tab a un array
     * @return type
     */
    public function toArray() 
    {
        $data = [
            "id" => $this->id,
            "name" => $this->name,
            "tabsContent" => [],
        ];
        
        foreach ($this->tabsContent as $tabContent) {
            $data["tabsContent"][] = $tabContent->toArray();
        }
        
        return $data;
    }
    
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }
    
    public function getRootUrl()
    {
        return $this->rootUrl;
    }

    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = $rootUrl;
        return $this;
    }
    
    /**
     * Busca un parametro
     * @param type $key
     * @return type
     */
    public function getParameter($key)
    {
        if(!isset($this->parameters[$key])){
            throw new RuntimeException(sprintf("The parameter '%s' is not exists.",$key));
        }
        return $this->parameters[$key];
    }

    public function setParameters(array $parameters = [])
    {
        $this->parameters = $parameters;
        return $this;
    }
}
