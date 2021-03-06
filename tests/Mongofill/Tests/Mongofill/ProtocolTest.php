<?php

namespace Mongofill\Tests\Mongofill;

use Mongofill\Tests\TestCase;
use Mongofill\Protocol;
use Mongofill\Socket;

class ProtocolTest extends TestCase
{
    private function getProtocol()
    {
        $socket = new Socket('localhost', 27017);
        $socket->connect();

        $proto = new Protocol($socket);

        return $proto;
    }

    public function testInsertPasses()
    {
        $conn = $this->getProtocol();
        $conn->opInsert('mongofill.instest', [ [ 'foo' => 'bar' ] ], [], 0);
    }

    public function testQuery()
    {
        $conn = $this->getProtocol();

        $res = $conn->opQuery('mongofill.instest', [], 0, 0, Protocol::QF_SLAVE_OK, 0);
        while ($res['result']) {
            $res = $conn->opGetMore('mongofill.instest', 10, $res['cursorId'], 0);
        }
    }

    public function testDelete()
    {
        $conn = $this->getProtocol();
        $conn->opDelete('mongofill.instest', [], [], 0);
    }
}
