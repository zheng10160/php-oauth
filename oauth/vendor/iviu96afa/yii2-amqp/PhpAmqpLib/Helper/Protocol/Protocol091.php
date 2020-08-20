<?php

/* This file was autogenerated by spec/parser.php - Do not modify */

namespace devmustafa\amqp\PhpAmqpLib\Helper\Protocol;

use devmustafa\amqp\PhpAmqpLib\Wire\AMQPWriter;

class Protocol091
{

    public function connectionStart(
        $version_major = 0,
        $version_minor = 9,
        $server_properties,
        $mechanisms = 'PLAIN',
        $locales = 'en_US'
    ) {
        $args = new AMQPWriter();
        $args->write_octet($version_major);
        $args->write_octet($version_minor);
        $args->write_table($server_properties);
        $args->write_longstr($mechanisms);
        $args->write_longstr($locales);
        return array(10, 10, $args);
    }

    public static function connectionStartOk($args)
    {
        $ret = array();
        $ret[] = $args->read_table();
        $ret[] = $args->read_shortstr();
        $ret[] = $args->read_longstr();
        $ret[] = $args->read_shortstr();
        return $ret;
    }

    public function connectionSecure($challenge)
    {
        $args = new AMQPWriter();
        $args->write_longstr($challenge);
        return array(10, 20, $args);
    }

    public static function connectionSecureOk($args)
    {
        $ret = array();
        $ret[] = $args->read_longstr();
        return $ret;
    }

    public function connectionTune($channel_max = 0, $frame_max = 0, $heartbeat = 0)
    {
        $args = new AMQPWriter();
        $args->write_short($channel_max);
        $args->write_long($frame_max);
        $args->write_short($heartbeat);
        return array(10, 30, $args);
    }

    public static function connectionTuneOk($args)
    {
        $ret = array();
        $ret[] = $args->read_short();
        $ret[] = $args->read_long();
        $ret[] = $args->read_short();
        return $ret;
    }

    public function connectionOpen($virtual_host = '/', $capabilities = '', $insist = false)
    {
        $args = new AMQPWriter();
        $args->write_shortstr($virtual_host);
        $args->write_shortstr($capabilities);
        $args->write_bit($insist);
        return array(10, 40, $args);
    }

    public static function connectionOpenOk($args)
    {
        $ret = array();
        $ret[] = $args->read_shortstr();
        return $ret;
    }

    public function connectionClose($reply_code, $reply_text = '', $class_id, $method_id)
    {
        $args = new AMQPWriter();
        $args->write_short($reply_code);
        $args->write_shortstr($reply_text);
        $args->write_short($class_id);
        $args->write_short($method_id);
        return array(10, 50, $args);
    }

    public static function connectionCloseOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function channelOpen($out_of_band = '')
    {
        $args = new AMQPWriter();
        $args->write_shortstr($out_of_band);
        return array(20, 10, $args);
    }

    public static function channelOpenOk($args)
    {
        $ret = array();
        $ret[] = $args->read_longstr();
        return $ret;
    }

    public function channelFlow($active)
    {
        $args = new AMQPWriter();
        $args->write_bit($active);
        return array(20, 20, $args);
    }

    public static function channelFlowOk($args)
    {
        $ret = array();
        $ret[] = $args->read_bit();
        return $ret;
    }

    public function channelClose($reply_code, $reply_text = '', $class_id, $method_id)
    {
        $args = new AMQPWriter();
        $args->write_short($reply_code);
        $args->write_shortstr($reply_text);
        $args->write_short($class_id);
        $args->write_short($method_id);
        return array(20, 40, $args);
    }

    public static function channelCloseOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function accessRequest(
        $realm = '/data',
        $exclusive = false,
        $passive = true,
        $active = true,
        $write = true,
        $read = true
    ) {
        $args = new AMQPWriter();
        $args->write_shortstr($realm);
        $args->write_bit($exclusive);
        $args->write_bit($passive);
        $args->write_bit($active);
        $args->write_bit($write);
        $args->write_bit($read);
        return array(30, 10, $args);
    }

    public static function accessRequestOk($args)
    {
        $ret = array();
        $ret[] = $args->read_short();
        return $ret;
    }

    public function exchangeDeclare(
        $ticket = 0,
        $exchange,
        $type = 'direct',
        $passive = false,
        $durable = false,
        $auto_delete = false,
        $internal = false,
        $nowait = false,
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($exchange);
        $args->write_shortstr($type);
        $args->write_bit($passive);
        $args->write_bit($durable);
        $args->write_bit($auto_delete);
        $args->write_bit($internal);
        $args->write_bit($nowait);
        $args->write_table($arguments);
        return array(40, 10, $args);
    }

    public static function exchangeDeclareOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function exchangeDelete($ticket = 0, $exchange, $if_unused = false, $nowait = false)
    {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($exchange);
        $args->write_bit($if_unused);
        $args->write_bit($nowait);
        return array(40, 20, $args);
    }

    public static function exchangeDeleteOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function exchangeBind(
        $ticket = 0,
        $destination,
        $source,
        $routing_key = '',
        $nowait = false,
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($destination);
        $args->write_shortstr($source);
        $args->write_shortstr($routing_key);
        $args->write_bit($nowait);
        $args->write_table($arguments);
        return array(40, 30, $args);
    }

    public static function exchangeBindOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function exchangeUnbind(
        $ticket = 0,
        $destination,
        $source,
        $routing_key = '',
        $nowait = false,
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($destination);
        $args->write_shortstr($source);
        $args->write_shortstr($routing_key);
        $args->write_bit($nowait);
        $args->write_table($arguments);
        return array(40, 40, $args);
    }

    public static function exchangeUnbindOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function queueDeclare(
        $ticket = 0,
        $queue = '',
        $passive = false,
        $durable = false,
        $exclusive = false,
        $auto_delete = false,
        $nowait = false,
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_bit($passive);
        $args->write_bit($durable);
        $args->write_bit($exclusive);
        $args->write_bit($auto_delete);
        $args->write_bit($nowait);
        $args->write_table($arguments);
        return array(50, 10, $args);
    }

    public static function queueDeclareOk($args)
    {
        $ret = array();
        $ret[] = $args->read_shortstr();
        $ret[] = $args->read_long();
        $ret[] = $args->read_long();
        return $ret;
    }

    public function queueBind(
        $ticket = 0,
        $queue = '',
        $exchange,
        $routing_key = '',
        $nowait = false,
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_shortstr($exchange);
        $args->write_shortstr($routing_key);
        $args->write_bit($nowait);
        $args->write_table($arguments);
        return array(50, 20, $args);
    }

    public static function queueBindOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function queuePurge($ticket = 0, $queue = '', $nowait = false)
    {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_bit($nowait);
        return array(50, 30, $args);
    }

    public static function queuePurgeOk($args)
    {
        $ret = array();
        $ret[] = $args->read_long();
        return $ret;
    }

    public function queueDelete($ticket = 0, $queue = '', $if_unused = false, $if_empty = false, $nowait = false)
    {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_bit($if_unused);
        $args->write_bit($if_empty);
        $args->write_bit($nowait);
        return array(50, 40, $args);
    }

    public static function queueDeleteOk($args)
    {
        $ret = array();
        $ret[] = $args->read_long();
        return $ret;
    }

    public function queueUnbind(
        $ticket = 0,
        $queue = '',
        $exchange,
        $routing_key = '',
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_shortstr($exchange);
        $args->write_shortstr($routing_key);
        $args->write_table($arguments);
        return array(50, 50, $args);
    }

    public static function queueUnbindOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function basicQos($prefetch_size = 0, $prefetch_count = 0, $global = false)
    {
        $args = new AMQPWriter();
        $args->write_long($prefetch_size);
        $args->write_short($prefetch_count);
        $args->write_bit($global);
        return array(60, 10, $args);
    }

    public static function basicQosOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function basicConsume(
        $ticket = 0,
        $queue = '',
        $consumer_tag = '',
        $no_local = false,
        $no_ack = false,
        $exclusive = false,
        $nowait = false,
        $arguments = array()
    ) {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_shortstr($consumer_tag);
        $args->write_bit($no_local);
        $args->write_bit($no_ack);
        $args->write_bit($exclusive);
        $args->write_bit($nowait);
        $args->write_table($arguments);
        return array(60, 20, $args);
    }

    public static function basicConsumeOk($args)
    {
        $ret = array();
        $ret[] = $args->read_shortstr();
        return $ret;
    }

    public function basicCancel($consumer_tag, $nowait = false)
    {
        $args = new AMQPWriter();
        $args->write_shortstr($consumer_tag);
        $args->write_bit($nowait);
        return array(60, 30, $args);
    }

    public static function basicCancelOk($args)
    {
        $ret = array();
        $ret[] = $args->read_shortstr();
        return $ret;
    }

    public function basicPublish($ticket = 0, $exchange = '', $routing_key = '', $mandatory = false, $immediate = false)
    {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($exchange);
        $args->write_shortstr($routing_key);
        $args->write_bit($mandatory);
        $args->write_bit($immediate);
        return array(60, 40, $args);
    }

    public function basicReturn($reply_code, $reply_text = '', $exchange, $routing_key)
    {
        $args = new AMQPWriter();
        $args->write_short($reply_code);
        $args->write_shortstr($reply_text);
        $args->write_shortstr($exchange);
        $args->write_shortstr($routing_key);
        return array(60, 50, $args);
    }

    public function basicDeliver($consumer_tag, $delivery_tag, $redelivered = false, $exchange, $routing_key)
    {
        $args = new AMQPWriter();
        $args->write_shortstr($consumer_tag);
        $args->write_longlong($delivery_tag);
        $args->write_bit($redelivered);
        $args->write_shortstr($exchange);
        $args->write_shortstr($routing_key);
        return array(60, 60, $args);
    }

    public function basicGet($ticket = 0, $queue = '', $no_ack = false)
    {
        $args = new AMQPWriter();
        $args->write_short($ticket);
        $args->write_shortstr($queue);
        $args->write_bit($no_ack);
        return array(60, 70, $args);
    }

    public static function basicGetOk($args)
    {
        $ret = array();
        $ret[] = $args->read_longlong();
        $ret[] = $args->read_bit();
        $ret[] = $args->read_shortstr();
        $ret[] = $args->read_shortstr();
        $ret[] = $args->read_long();
        return $ret;
    }

    public static function basicGetEmpty($args)
    {
        $ret = array();
        $ret[] = $args->read_shortstr();
        return $ret;
    }

    public function basicAck($delivery_tag = 0, $multiple = false)
    {
        $args = new AMQPWriter();
        $args->write_longlong($delivery_tag);
        $args->write_bit($multiple);
        return array(60, 80, $args);
    }

    public function basicReject($delivery_tag, $requeue = true)
    {
        $args = new AMQPWriter();
        $args->write_longlong($delivery_tag);
        $args->write_bit($requeue);
        return array(60, 90, $args);
    }

    public function basicRecoverAsync($requeue = false)
    {
        $args = new AMQPWriter();
        $args->write_bit($requeue);
        return array(60, 100, $args);
    }

    public function basicRecover($requeue = false)
    {
        $args = new AMQPWriter();
        $args->write_bit($requeue);
        return array(60, 110, $args);
    }

    public static function basicRecoverOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function basicNack($delivery_tag = 0, $multiple = false, $requeue = true)
    {
        $args = new AMQPWriter();
        $args->write_longlong($delivery_tag);
        $args->write_bit($multiple);
        $args->write_bit($requeue);
        return array(60, 120, $args);
    }

    public function txSelect()
    {
        $args = new AMQPWriter();
        return array(90, 10, $args);
    }

    public static function txSelectOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function txCommit()
    {
        $args = new AMQPWriter();
        return array(90, 20, $args);
    }

    public static function txCommitOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function txRollback()
    {
        $args = new AMQPWriter();
        return array(90, 30, $args);
    }

    public static function txRollbackOk($args)
    {
        $ret = array();
        return $ret;
    }

    public function confirmSelect($nowait = false)
    {
        $args = new AMQPWriter();
        $args->write_bit($nowait);
        return array(85, 10, $args);
    }

    public static function confirmSelectOk($args)
    {
        $ret = array();
        return $ret;
    }
}