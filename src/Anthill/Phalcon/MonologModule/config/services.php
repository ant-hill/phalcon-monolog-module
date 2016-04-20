<?php
return array(

    'monolog.handler.stream' => array(
        'className' => Monolog\Handler\StreamHandler::class,
        'arguments' => array(
            array(
                'type' => 'config_parameter',
                'value' => 'monolog.log_path'
            ),
            array(
                'type' => 'config_parameter',
                'value' => 'monolog.log_level'
            ),
        ),
    ),

    'monolog' => array(
        'className' => Monolog\Logger::class,
        'arguments' => array(
            array(
                'type' => 'parameter',
                'value' => 'main',
            )
        ),
        'calls' => array(
            array(
                'method' => 'pushHandler',
                'arguments' => array(
                    array(
                        'type' => 'service',
                        'name' => 'monolog.handler.stream'
                    )
                )
            )
        )
    )
);