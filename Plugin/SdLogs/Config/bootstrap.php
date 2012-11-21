<?php

App::uses('SdLogs', 'SdLogs.Lib');

CakeEventManager::instance()->attach(new SdLogs());