<?php
/**
 * Created by PhpStorm.
 * User: 82140
 * Date: 21/03/2019
 * Time: 14:16
 */

// AD settings
$ad_options = [
    'account_suffix'     => '@ict.lab.locals',
    'base_dn'            => 'DC=ict,DC=lab,DC=locals',
    'domain_controllers' => ['dc1.ict.lab.locals', 'dc2.ict.lab.locals'],
    'admin_username'     => null,
    'admin_password'     => null,
    'real_primarygroup'  => true,
    'use_ssl'            => false,
    'use_tls'            => false,
    'recursive_groups'   => true
];