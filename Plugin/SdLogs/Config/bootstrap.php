<?php

App::uses('SdLogManager', 'SdLogs.Lib');
App::uses('CakeEventManager', 'Event');

CakeEventManager::instance()->attach(new SdLogManager());