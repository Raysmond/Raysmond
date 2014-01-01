<?php
/**
 * SiteController
 *
 * @author: Raysmond
 * @created: 2014-01-01
 */

class SiteController extends RController
{
    // public $defaultAction = "index";
    // public $layout = "index";
    public $access = array(
        User::ADMIN => array("config")
    );

    public function actionIndex()
    {
        $this->render("index", array());
    }

    public function actionAbout()
    {
        $aboutPage = Page::get(5);
        $this->render("about", array('page' => $aboutPage));
    }

    public function actionContact()
    {
        if (Rays::isPost()) {
            // do some thing

            $this->flash("message", "Thanks for your contact!");
        }
        $this->setHeaderTitle("Contact");
        $this->render("contact");
    }

    public function actionConfig()
    {
        $config = Variable::get("site_configuration");
        if (Rays::isPost()) {
            if ($config === null)
                $config = new Variable();
            // validations
            $config->value = array_merge($config->value, $_POST);
            if ($config->validate_save() === false) {
                $this->render("config", array('config' => $config, 'errors' => $config->getErrors()));
            }
        }
        $this->render("config", array('config' => ($config === null ? null : $config->value)));
    }

    /**
     * Exception handling
     * @param Exception $e
     */
    public function actionException(Exception $e)
    {
        if ($e instanceof RPageNotFoundException || $e->getCode() === 404) {
            $this->setHeaderTitle("404");
            $this->renderContent("<h1>404, page not found!</h1>");
            return;
        }

        if (Rays::app()->isDebug())
            print $e;
        else
            $this->renderContent($e->getCode() . '<br/>' . $e->getMessage());
    }
}