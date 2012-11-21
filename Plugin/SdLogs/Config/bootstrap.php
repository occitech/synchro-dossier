<?php

App::uses('SdLogs', 'SdLogs.Lib');
App::uses('CakeEventManager', 'Event');

CakeEventManager::instance()->attach(new SdLogs());