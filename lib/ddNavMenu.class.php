<?php // lib/ddNavMenu.class.php
  class ddNavMenu extends ddNavMenuItem {
    protected
      $_menu      = array(),
      $_renderer  = 'ddNavMenuRenderer',
      $_request   = false,
      $_context;

    public function __constructor() { 
      $this->_context = new StdClass();
      parent::construct(null,null,array()); }

    public function setRenderer($r) {
      if(!class_exists($r)) throw new sfException('Renderer not found. Could not locate renderer '.$r.'.');
      $this->_renderer = $r;
    }

    public function setRequest(sfWebRequest $r) { $this->_request = $r; }

    public function setModule($m) { $this->_context->module = $m; }
    public function setAction($a) { $this->_context->action = $a; }
    //public function setUser($u) { $this->_context->user = $u; }

    public function addItem(ddNavMenu $i) {
        $i->setRequest($this->_request)->setContext($this->_context);
        $this->_menu[$i->getName()] = $i;
            foreach ($i->getChildren() as $child)
                $child->setRequest($this->_request)->setContext($this->_context);
    }
    public function getItem($n) { return (isset($this->_menu[$n]) ? $this->_menu[$n] : false); }
    public function addItems(Array $i) {
        foreach ($i as $item) {
            if(!$item instanceof ddNavMenuItem)
                throw new sfPluginException("Each menu item must be an instance of ddNavMenuItem", 1);
            $this->addItem($item);
        }
    }
    public function getItems() { return $this->_menu; }
  }
