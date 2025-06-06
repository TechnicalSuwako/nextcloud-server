<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2016-2024 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2016 ownCloud, Inc.
 * SPDX-License-Identifier: AGPL-3.0-only
 */
// use OCP namespace for all classes that are considered public.
// This means that they should be used by apps instead of the internal Nextcloud classes

namespace OCP;

/**
 * public api to access default strings and urls for your templates
 * @since 6.0.0
 */
class Defaults {
	/**
	 * \OC_Defaults instance to retrieve the defaults
	 * @since 6.0.0
	 */
	private $defaults;

	/**
	 * creates a \OC_Defaults instance which is used in all methods to retrieve the
	 * actual defaults
	 * @since 6.0.0
	 */
	public function __construct(?\OC_Defaults $defaults = null) {
		if ($defaults === null) {
			$defaults = \OCP\Server::get('ThemingDefaults');
		}
		$this->defaults = $defaults;
	}

	/**
	 * get base URL for the organisation behind your ownCloud instance
	 * @return string
	 * @since 6.0.0
	 */
	public function getBaseUrl(): string {
		return $this->defaults->getBaseUrl();
	}

	/**
	 * link to the desktop sync client
	 * @return string
	 * @since 6.0.0
	 */
	public function getSyncClientUrl(): string {
		return $this->defaults->getSyncClientUrl();
	}

	/**
	 * link to the iOS client
	 * @return string
	 * @since 8.0.0
	 */
	public function getiOSClientUrl(): string {
		return $this->defaults->getiOSClientUrl();
	}

	/**
	 * link to the Android client
	 * @return string
	 * @since 8.0.0
	 */
	public function getAndroidClientUrl(): string {
		return $this->defaults->getAndroidClientUrl();
	}

	/**
	 * link to the Android client on F-Droid
	 * @return string
	 * @since 23.0.0
	 */
	public function getFDroidClientUrl() {
		return $this->defaults->getFDroidClientUrl();
	}

	/**
	 * base URL to the documentation of your ownCloud instance
	 * @return string
	 * @since 6.0.0
	 */
	public function getDocBaseUrl(): string {
		return $this->defaults->getDocBaseUrl();
	}

	/**
	 * name of your Nextcloud instance (e.g. MyPrivateCloud)
	 * @return string
	 * @since 6.0.0
	 */
	public function getName(): string {
		return $this->defaults->getName();
	}

	/**
	 * Name of the software product (defaults to Nextcloud)
	 *
	 * @return string
	 * @since 22.0.0
	 */
	public function getProductName(): string {
		return $this->defaults->getProductName();
	}

	/**
	 * name of your ownCloud instance containing HTML styles
	 * @return string
	 * @since 8.0.0
	 * @deprecated 22.0.0
	 */
	public function getHTMLName(): string {
		return $this->defaults->getHTMLName();
	}

	/**
	 * Entity behind your onwCloud instance
	 * @return string
	 * @since 6.0.0
	 */
	public function getEntity(): string {
		return $this->defaults->getEntity();
	}

	/**
	 * ownCloud slogan
	 * @return string
	 * @since 6.0.0
	 */
	public function getSlogan(?string $lang = null): string {
		return $this->defaults->getSlogan($lang);
	}

	/**
	 * footer, short version
	 * @return string
	 * @since 6.0.0
	 */
	public function getShortFooter(): string {
		return $this->defaults->getShortFooter();
	}

	/**
	 * footer, long version
	 * @return string
	 * @since 6.0.0
	 */
	public function getLongFooter(): string {
		return $this->defaults->getLongFooter();
	}

	/**
	 * Returns the AppId for the App Store for the iOS Client
	 * @return string AppId
	 * @since 8.0.0
	 */
	public function getiTunesAppId(): string {
		return $this->defaults->getiTunesAppId();
	}

	/**
	 * Themed logo url
	 *
	 * @param bool $useSvg Whether to point to the SVG image or a fallback
	 * @return string
	 * @since 12.0.0
	 */
	public function getLogo(bool $useSvg = true): string {
		return $this->defaults->getLogo($useSvg);
	}

	/**
	 * Returns primary color
	 * @return string
	 * @since 12.0.0
	 */
	public function getColorPrimary(): string {
		return $this->defaults->getColorPrimary();
	}

	/**
	 * Return the default color primary
	 * @return string
	 * @since 25.0.4
	 */
	public function getDefaultColorPrimary(): string {
		if (method_exists($this->defaults, 'getDefaultColorPrimary')) {
			return $this->defaults->getDefaultColorPrimary();
		}
		return $this->defaults->getColorPrimary();
	}

	/**
	 * @param string $key
	 * @return string URL to doc with key
	 * @since 12.0.0
	 */
	public function buildDocLinkToKey(string $key): string {
		return $this->defaults->buildDocLinkToKey($key);
	}

	/**
	 * Returns the title
	 * @return string title
	 * @since 12.0.0
	 */
	public function getTitle(): string {
		return $this->defaults->getTitle();
	}

	/**
	 * Returns primary color
	 * @return string
	 * @since 13.0.0
	 */
	public function getTextColorPrimary(): string {
		return $this->defaults->getTextColorPrimary();
	}

	/**
	 * Returns primary color
	 * @return string
	 * @since 25.0.4
	 */
	public function getDefaultTextColorPrimary(): string {
		if (method_exists($this->defaults, 'getDefaultTextColorPrimary')) {
			return $this->defaults->getDefaultTextColorPrimary();
		}
		return $this->defaults->getTextColorPrimary();
	}
}
