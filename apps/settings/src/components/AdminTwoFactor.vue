<!--
  - SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
  - SPDX-License-Identifier: AGPL-3.0-or-later
-->
<template>
	<NcSettingsSection :name="t('settings', 'Two-Factor Authentication')"
		:description="t('settings', 'Two-factor authentication can be enforced for all accounts and specific groups. If they do not have a two-factor provider configured, they will be unable to log into the system.')"
		:doc-url="twoFactorAdminDoc">
		<p v-if="loading">
			<span class="icon-loading-small two-factor-loading" />
			<span>{{ t('settings', 'Enforce two-factor authentication') }}</span>
		</p>
		<NcCheckboxRadioSwitch v-else
			id="two-factor-enforced"
			:checked.sync="enforced"
			type="switch">
			{{ t('settings', 'Enforce two-factor authentication') }}
		</NcCheckboxRadioSwitch>
		<template v-if="enforced">
			<h3>{{ t('settings', 'Limit to groups') }}</h3>
			{{ t('settings', 'Enforcement of two-factor authentication can be set for certain groups only.') }}
			<p class="top-margin">
				{{ t('settings', 'Two-factor authentication is enforced for all members of the following groups.') }}
			</p>
			<p>
				<label for="enforcedGroups">
					<span>{{ t('settings', 'Enforced groups') }}</span>
				</label>
				<NcSelect v-model="enforcedGroups"
					input-id="enforcedGroups"
					:options="groups"
					:disabled="loading"
					:multiple="true"
					:loading="loadingGroups"
					:close-on-select="false"
					@search="searchGroup" />
			</p>
			<p class="top-margin">
				{{ t('settings', 'Two-factor authentication is not enforced for members of the following groups.') }}
			</p>
			<p>
				<label for="excludedGroups">
					<span>{{ t('settings', 'Excluded groups') }}</span>
				</label>
				<NcSelect v-model="excludedGroups"
					input-id="excludedGroups"
					:options="groups"
					:disabled="loading"
					:multiple="true"
					:loading="loadingGroups"
					:close-on-select="false"
					@search="searchGroup" />
			</p>
			<p class="top-margin">
				<em>
					<!-- this text is also found in the documentation. update it there as well if it ever changes -->
					{{ t('settings', 'When groups are selected/excluded, they use the following logic to determine if an account has 2FA enforced: If no groups are selected, 2FA is enabled for everyone except members of the excluded groups. If groups are selected, 2FA is enabled for all members of these. If an account is both in a selected and excluded group, the selected takes precedence and 2FA is enforced.') }}
				</em>
			</p>
		</template>
		<p class="top-margin">
			<NcButton v-if="dirty"
				type="primary"
				:disabled="loading"
				@click="saveChanges">
				{{ t('settings', 'Save changes') }}
			</NcButton>
		</p>
	</NcSettingsSection>
</template>

<script>
import axios from '@nextcloud/axios'
import NcSelect from '@nextcloud/vue/components/NcSelect'
import NcButton from '@nextcloud/vue/components/NcButton'
import NcCheckboxRadioSwitch from '@nextcloud/vue/components/NcCheckboxRadioSwitch'
import NcSettingsSection from '@nextcloud/vue/components/NcSettingsSection'
import { loadState } from '@nextcloud/initial-state'

import sortedUniq from 'lodash/sortedUniq.js'
import uniq from 'lodash/uniq.js'
import debounce from 'lodash/debounce.js'
import { generateUrl, generateOcsUrl } from '@nextcloud/router'

export default {
	name: 'AdminTwoFactor',
	components: {
		NcSelect,
		NcButton,
		NcCheckboxRadioSwitch,
		NcSettingsSection,
	},
	data() {
		return {
			loading: false,
			dirty: false,
			groups: [],
			loadingGroups: false,
			twoFactorAdminDoc: loadState('settings', 'two-factor-admin-doc'),
		}
	},
	computed: {
		enforced: {
			get() {
				return this.$store.state.enforced
			},
			set(val) {
				this.dirty = true
				this.$store.commit('setEnforced', val)
			},
		},
		enforcedGroups: {
			get() {
				return this.$store.state.enforcedGroups
			},
			set(val) {
				this.dirty = true
				this.$store.commit('setEnforcedGroups', val)
			},
		},
		excludedGroups: {
			get() {
				return this.$store.state.excludedGroups
			},
			set(val) {
				this.dirty = true
				this.$store.commit('setExcludedGroups', val)
			},
		},
	},
	mounted() {
		// Groups are loaded dynamically, but the assigned ones *should*
		// be valid groups, so let's add them as initial state
		this.groups = sortedUniq(uniq(this.enforcedGroups.concat(this.excludedGroups)))

		// Populate the groups with a first set so the dropdown is not empty
		// when opening the page the first time
		this.searchGroup('')
	},
	methods: {
		searchGroup: debounce(function(query) {
			this.loadingGroups = true
			axios.get(generateOcsUrl('cloud/groups?offset=0&search={query}&limit=20', { query }))
				.then(res => res.data.ocs)
				.then(ocs => ocs.data.groups)
				.then(groups => { this.groups = sortedUniq(uniq(this.groups.concat(groups))) })
				.catch(err => console.error('could not search groups', err))
				.then(() => { this.loadingGroups = false })
		}, 500),

		saveChanges() {
			this.loading = true

			const data = {
				enforced: this.enforced,
				enforcedGroups: this.enforcedGroups,
				excludedGroups: this.excludedGroups,
			}
			axios.put(generateUrl('/settings/api/admin/twofactorauth'), data)
				.then(resp => resp.data)
				.then(state => {
					this.state = state
					this.dirty = false
				})
				.catch(err => {
					console.error('could not save changes', err)
				})
				.then(() => { this.loading = false })
		},
	},
}
</script>

<style scoped>
	.two-factor-loading {
		display: inline-block;
		vertical-align: sub;
		margin-inline: -2px 1px;
	}

	.top-margin {
		margin-top: 0.5rem;
	}
</style>
