<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Page\Shop\Contact\ContactPageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Webmozart\Assert\Assert;

final class ContactContext implements Context
{
    public function __construct(
        private ContactPageInterface $contactPage,
        private NotificationCheckerInterface $notificationChecker,
    ) {
    }

    /**
     * @When I want to request contact
     */
    public function iWantToRequestContact()
    {
        $this->contactPage->open();
    }

    /**
     * @When I specify the email as :email
     * @When I do not specify the email
     */
    public function iSpecifyTheEmail($email = null)
    {
        $this->contactPage->specifyEmail($email);
    }

    /**
     * @When I specify the message as :message
     * @When I do not specify the message
     */
    public function iSpecifyTheMessage($message = null)
    {
        $this->contactPage->specifyMessage($message);
    }

    /**
     * @When I send it
     * @When I try to send it
     */
    public function iSendIt()
    {
        $this->contactPage->send();
    }

    /**
     * @Then I should be notified that the contact request has been submitted successfully
     */
    public function iShouldBeNotifiedThatTheContactRequestHasBeenSubmittedSuccessfully()
    {
        $this->notificationChecker->checkNotification(
            'Your contact request has been submitted successfully.',
            NotificationType::success(),
        );
    }

    /**
     * @Then /^I should be notified that the (email|message) is required$/
     */
    public function iShouldBeNotifiedThatElementIsRequired($element)
    {
        Assert::same($this->contactPage->getValidationMessageFor($element), sprintf('Please enter your %s.', $element));
    }

    /**
     * @Then I should be notified that the email is invalid
     */
    public function iShouldBeNotifiedThatEmailIsInvalid()
    {
        Assert::same($this->contactPage->getValidationMessageFor('email'), 'This email is invalid.');
    }

    /**
     * @Then I should be notified that a problem occurred while sending the contact request
     */
    public function iShouldBeNotifiedThatAProblemOccurredWhileSendingTheContactRequest()
    {
        $this->notificationChecker->checkNotification(
            'A problem occurred while sending the contact request. Please try again later.',
            NotificationType::failure(),
        );
    }
}
