<?php

namespace humandirect\cookiebot;

use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;

use humandirect\cookiebot\models\Settings;
use humandirect\cookiebot\services\CookiebotService;
use humandirect\cookiebot\variables\CookiebotVariable;

use yii\base\Event;

/**
 * Cookiebot class
 *
 * @author    Balazs Csaba <csaba.balazs@humandirect.eu>
 * @copyright 2018 Human Direct
 */
class Cookiebot extends Plugin
{
    /**
     * @var CookiebotService
     */
    public static $plugin;

    /**
     * Initialize plugin.
     */
    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $event) {
            /** @var CraftVariable $variable */
            $variable = $event->sender;
            $variable->set('cookiebot', CookiebotVariable::class);
        });

        $this->setComponents(['service' => CookiebotService::class]);

        \Craft::info(
            \Craft::t('cookiebot', '{name} plugin loaded', [
                'name' => $this->name
            ]),
            __METHOD__
        );
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     * @throws \Twig_Error_Loader
     * @throws \RuntimeException
     * @throws \yii\base\Exception
     */
    protected function settingsHtml(): string
    {
        return \Craft::$app->view->renderTemplate(
            'cookiebot/settings',
            [
                'settings' => $this->getSettings(),
            ]
        );
    }
}