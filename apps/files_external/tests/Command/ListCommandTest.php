<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
namespace OCA\Files_External\Tests\Command;

use OCA\Files_External\Command\ListCommand;
use OCA\Files_External\Lib\Auth\NullMechanism;
use OCA\Files_External\Lib\Auth\Password\Password;
use OCA\Files_External\Lib\Auth\Password\SessionCredentials;
use OCA\Files_External\Lib\Backend\Local;
use OCA\Files_External\Lib\StorageConfig;
use OCA\Files_External\Service\GlobalStoragesService;
use OCA\Files_External\Service\UserStoragesService;
use OCP\Authentication\LoginCredentials\IStore;
use OCP\IL10N;
use OCP\IUserManager;
use OCP\IUserSession;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Output\BufferedOutput;

class ListCommandTest extends CommandTestCase {
	private function getInstance(): ListCommand {
		/** @var GlobalStoragesService&MockObject $globalService */
		$globalService = $this->createMock(GlobalStoragesService::class);
		/** @var UserStoragesService&MockObject $userService */
		$userService = $this->createMock(UserStoragesService::class);
		/** @var IUserManager&MockObject $userManager */
		$userManager = $this->createMock(IUserManager::class);
		/** @var IUserSession&MockObject $userSession */
		$userSession = $this->createMock(IUserSession::class);

		return new ListCommand($globalService, $userService, $userSession, $userManager);
	}

	public function testListAuthIdentifier(): void {
		$l10n = $this->createMock(IL10N::class);
		$instance = $this->getInstance();
		$mount1 = new StorageConfig();
		$mount1->setAuthMechanism(new Password($l10n));
		$mount1->setBackend(new Local($l10n, new NullMechanism($l10n)));
		$mount2 = new StorageConfig();
		$credentialStore = $this->createMock(IStore::class);
		$mount2->setAuthMechanism(new SessionCredentials($l10n, $credentialStore));
		$mount2->setBackend(new Local($l10n, new NullMechanism($l10n)));
		$input = $this->getInput($instance, [], [
			'output' => 'json'
		]);
		$output = new BufferedOutput();

		$instance->listMounts('', [$mount1, $mount2], $input, $output);
		$output = json_decode($output->fetch(), true);

		$this->assertNotEquals($output[0]['authentication_type'], $output[1]['authentication_type']);
	}
}
