<?php

namespace console\modules\backup;

use console\modules\backup\transports\Ftp;
use console\modules\backup\transports\Mail;
use Yii;
use yii\console\Application as ConsoleApplication;
use yii\helpers\ArrayHelper;
/**
 * modules module definition class
 */
class Module extends \yii\base\Module
{
    const TYPE_DB     = 'db';

    const TYPE_FOLDER = 'folder';

    public $defaultRoute = 'all/index';

    public $backupPath   = '@runtime/backup';

    public $transport    = [];

    public $backup       = [];

    /**
     * {@inheritDoc}
     */
    public function init() {
        parent::init();
        if (\Yii::$app instanceof ConsoleApplication) {
            $this->controllerNamespace = 'navatech\backup\commands';
        } else {
            $this->controllerNamespace = 'navatech\backup\controllers';
            $this->defaultRoute        = 'default/index';
        }
        $this->backupPath = Yii::getAlias($this->backupPath);
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
        $this->backup    = ArrayHelper::merge([
            'db'     => [
                'enable' => true,
                'data'   => [
                    'db',
                ],
            ],
            'folder' => [
                'enable' => false,
                'data'   => [
                    '@app/web/uploads',
                ],
            ],
        ], $this->backup);
        $this->transport = ArrayHelper::merge([
            'mail' => [
                'class'     => '\navatech\backup\transports\Mail',
                'enable'    => true,
                'fromEmail' => 'support@email.com',
                'toEmail'   => 'backup@email.com',
            ],
            'ftp'  => [
                'class'      => '\navatech\backup\transports\Ftp',
                'enable'     => false,
                'host'       => '',
                'port'       => 21,
                'ssl'        => false,
                'user'       => '',
                'pass'       => '',
                'dir'        => '',
                'timeOut'    => 90,
                'appendTime' => true,
            ],
        ], $this->transport);
    }

    /**
     * @return bool
     */
    public function backupDbEnable() {
        return $this->backup['db']['enable'];
    }

    /**
     * @return array
     */
    public function backupDbData() {
        return array_unique($this->backup['db']['data']);
    }

    /**
     * @return bool
     */
    public function backupFolderEnable() {
        return $this->backup['folder']['enable'];
    }

    /**
     * @return array
     */
    public function backupFolderData() {
        return array_unique($this->backup['folder']['data']);
    }

    /**
     * @return Mail
     */
    public function getMail() {
        $mailClass = $this->transport['mail']['class'];
        return new $mailClass($this->transport['mail']);
    }

    /**
     * @return Ftp
     */
    public function getFtp() {
        $ftpClass = $this->transport['ftp']['class'];
        return new $ftpClass($this->transport['ftp']);
    }
}
