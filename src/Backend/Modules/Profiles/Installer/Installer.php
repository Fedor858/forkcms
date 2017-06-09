<?php

namespace Backend\Modules\Profiles\Installer;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Installer\ModuleInstaller;
use Common\ModuleExtraType;
use Symfony\Component\Filesystem\Filesystem;
use Backend\Core\Language\Language;

/**
 * Installer for the profiles module.
 */
class Installer extends ModuleInstaller
{
    /** @var array */
    private $extraIds;

    public function install(): void
    {
        $this->addModule('Profiles');
        $this->importSQL(__DIR__ . '/Data/install.sql');
        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureSettings();
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->configureFrontendExtras();
        $this->configureFrontendFilesDirectories();
        $this->configureFrontendPages();
    }

    private function configureBackendActionRightsForGroup(): void
    {
        $this->setActionRights(1, $this->getModule(), 'AddGroup');
        $this->setActionRights(1, $this->getModule(), 'DeleteGroup');
        $this->setActionRights(1, $this->getModule(), 'EditGroup');
        $this->setActionRights(1, $this->getModule(), 'Groups');
    }

    private function configureBackendActionRightsForProfile(): void
    {
        $this->setActionRights(1, $this->getModule(), 'Add');
        $this->setActionRights(1, $this->getModule(), 'Block');
        $this->setActionRights(1, $this->getModule(), 'Delete');
        $this->setActionRights(1, $this->getModule(), 'Edit');
        $this->setActionRights(1, $this->getModule(), 'ExportTemplate');
        $this->setActionRights(1, $this->getModule(), 'Import');
        $this->setActionRights(1, $this->getModule(), 'Index');
        $this->setActionRights(1, $this->getModule(), 'MassAction');
    }

    private function configureBackendActionRightsForProfileGroup(): void
    {
        $this->setActionRights(1, $this->getModule(), 'AddProfileGroup');
        $this->setActionRights(1, $this->getModule(), 'DeleteProfileGroup');
        $this->setActionRights(1, $this->getModule(), 'EditProfileGroup');
    }

    private function configureBackendNavigation(): void
    {
        // Set navigation for "Modules"
        $navigationModulesId = $this->setNavigation(null, 'Modules');
        $navigationProfilesId = $this->setNavigation($navigationModulesId, 'Profiles');
        $this->setNavigation(
            $navigationProfilesId,
            'Overview',
            'profiles/index',
            [
                'profiles/add',
                'profiles/edit',
                'profiles/add_profile_group',
                'profiles/edit_profile_group',
                'profiles/import',
            ]
        );
        $this->setNavigation(
            $navigationProfilesId,
            'Groups',
            'profiles/groups',
            [
                'profiles/add_group',
                'profiles/edit_group',
            ]
        );

        // Set navigation for "Settings"
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, 'Profiles', 'profiles/settings');
    }

    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, $this->getModule());

        $this->configureBackendActionRightsForGroup();
        $this->configureBackendActionRightsForProfile();
        $this->configureBackendActionRightsForProfileGroup();
    }

    private function configureFrontendExtras(): void
    {
        $this->extraIds['activate'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'Activate',
            'Activate',
            null,
            false,
            5000
        );
        $this->extraIds['forgot_password'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'ForgotPassword',
            'ForgotPassword',
            null,
            false,
            5001
        );
        $this->extraIds['index'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'Dashboard',
            null,
            null,
            false,
            5002
        );
        $this->extraIds['login'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'Login',
            'Login',
            null,
            false,
            5003
        );
        $this->extraIds['logout'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'Logout',
            'Logout',
            null,
            false,
            5004
        );
        $this->extraIds['change_email'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'ChangeEmail',
            'ChangeEmail',
            null,
            false,
            5005
        );
        $this->extraIds['change_password'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'ChangePassword',
            'ChangePassword',
            null,
            false,
            5006
        );
        $this->extraIds['settings'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'Settings',
            'Settings',
            null,
            false,
            5007
        );
        $this->extraIds['register'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'Register',
            'Register',
            null,
            false,
            5008
        );
        $this->extraIds['reset_password'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'ResetPassword',
            'ResetPassword',
            null,
            false,
            5008
        );
        $this->extraIds['resend_activation'] = $this->insertExtra(
            $this->getModule(),
            ModuleExtraType::block(),
            'ResendActivation',
            'ResendActivation',
            null,
            false,
            5009
        );

        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'LoginBox', 'LoginBox', null, false, 5010);
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'LoginLink', 'LoginLink', null, false, 5011);
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'SecurePage', 'SecurePage', null, false, 5012);
    }

    private function configureFrontendFilesDirectories(): void
    {
        $filesystem = new Filesystem();
        $filesystem->mkdir(PATH_WWW . '/src/Frontend/Files/Profiles/avatars/source/');
        $filesystem->mkdir(PATH_WWW . '/src/Frontend/Files/Profiles/avatars/240x240/');
        $filesystem->mkdir(PATH_WWW . '/src/Frontend/Files/Profiles/avatars/64x64/');
        $filesystem->mkdir(PATH_WWW . '/src/Frontend/Files/Profiles/avatars/32x32/');
    }

    private function configureFrontendPages(): void
    {
        $originalLocale = Language::getInterfaceLanguage();

        // get search widget id
        $searchExtraId = $this->getSearchWidgetId();

        // loop languages
        foreach ($this->getLanguages() as $language) {
            // only add pages if profiles isn't linked anywhere
            // @todo refactor me, syntax sucks atm
            if (!(bool) $this->getDB()->getVar(
                'SELECT 1
                 FROM pages AS p
                 INNER JOIN pages_blocks AS b ON b.revision_id = p.revision_id
                 INNER JOIN modules_extras AS e ON e.id = b.extra_id
                 WHERE e.module = ? AND p.language = ?
                 LIMIT 1',
                [$this->getModule(), $language]
            )
            ) {
                // We must define the locale we want to insert the page into
                Language::setLocale($language);

                // activate page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('Activate')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('activate'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // forgot password page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('ForgotPassword')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('forgot_password'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // reset password page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('ResetPassword')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('reset_password'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // resend activation email page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('ResendActivation')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('resend_activation'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // login page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('Login')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('login'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // register page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('Register')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('register'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // logout page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('Logout')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('logout'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // index page
                $indexPageId = $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('Profile')),
                        'type' => 'root',
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('index'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // settings page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('ProfileSettings')),
                        'parent_id' => $indexPageId,
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('settings'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // change email page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('ChangeEmail')),
                        'parent_id' => $indexPageId,
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('change_email'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );

                // change password page
                $this->insertPage(
                    [
                        'title' => ucfirst(Language::lbl('ChangePassword')),
                        'parent_id' => $indexPageId,
                        'language' => $language,
                    ],
                    null,
                    ['extra_id' => $this->getExtraId('change_password'), 'position' => 'main'],
                    ['extra_id' => $searchExtraId, 'position' => 'top']
                );
            }
        }

        // restore the original locale
        if (!empty($originalLocale)) {
            Language::setLocale($originalLocale);
        }
    }

    private function configureSettings(): void
    {
        $this->setSetting($this->getModule(), 'allow_gravatar', true);
        $this->setSetting($this->getModule(), 'overwrite_profile_notification_email', false);
        $this->setSetting($this->getModule(), 'profile_notification_email', null);
        $this->setSetting($this->getModule(), 'send_mail_for_new_profile_to_admin', false);
        $this->setSetting($this->getModule(), 'send_mail_for_new_profile_to_profile', false);
    }

    private function getExtraId(string $key): int
    {
        if (!array_key_exists($key, $this->extraIds)) {
            throw new \Exception('Key not set yet, please check your installer.');
        }

        return $this->extraIds[$key];
    }

    private function getSearchWidgetId(): int
    {
        return (int) $this->getDB()->getVar(
            'SELECT id FROM modules_extras WHERE module = ? AND action = ?',
            ['Search', 'Form']
        );
    }
}
