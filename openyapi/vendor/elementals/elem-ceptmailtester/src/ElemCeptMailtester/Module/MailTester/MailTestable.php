<?php

namespace ElemCeptMailtester\Module\MailTester;

interface MailTestable
{
    /**
     * @return void
     */
    public function deleteAllEmails();

    /**
     * @return array
     */
    public function getAllEmails();

    /**
     * @return MailTesterMessage
     */
    public function getLastEmail();
}