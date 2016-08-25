
<?php
/**
 * hhvm -vDynamicExtensions.0=./hhvm_swoole.so test.php
 */

$serv = new Swoole\Server("127.0.0.1", 9501, SWOOLE_PROCESS);

$serv->on("workerStart", function($serv, $workerId) {
    //var_dump($serv);
});

$serv->on("receive", function($serv, $fd, $reactorId, $data) {
    var_dump($serv, $fd, $reactorId, $data);
    $serv->send($fd, "Swoole: $data");
    $serv->task("hello world");
});

$serv->on("connect", function($serv, $fd, $reactorId) {
    echo "Client#$fd connect\n";
    var_dump($serv->getClientInfo($fd));
});

$serv->on("close", function($serv, $fd, $reactorId) {
    echo "Client#$fd close\n";
});

$serv->on("task", function($serv, $task_id, $from_id, $data) {
    var_dump( $task_id, $from_id, $data);
    return array("tt" => time(), "data" => "hhvm");
});

$serv->on("finish", function($serv, $task_id, $result) {
    var_dump($task_id, $result);
});

$serv->set(array("task_worker_num" => 2, "worker_num" => 2));

$serv->start();