<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Kernel\Communication\Plugin\Fixture;

use SprykerEngine\Shared\Transfer\TransferInterface;

class Request extends \SprykerFeature\Shared\Library\Communication\Request
{

    /**
     * @var TransferInterface
     */
    private $transfer;

    /**
     * @return TransferInterface
     */
    public function getTransfer()
    {
        if ($this->transfer) {
            return $this->transfer;
        }

        return parent::getTransfer();
    }

    /**
     * @param TransferInterface $transfer
     *
     * @return self
     */
    public function setTransfer(TransferInterface $transfer)
    {
        $this->transfer = $transfer;

        return $this;
    }

    /**
     * @param $transfer
     *
     * @return void
     */
    public function setFixtureTransfer($transfer)
    {
        $this->transfer = $transfer;
    }

}
