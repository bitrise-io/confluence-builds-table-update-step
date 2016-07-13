<?php
namespace DAG\JIRA\BuildsTable;

/**
 * Class JSONBuilder
 */
final class JSONBuilder
{
    public function build(array $fromBuilds)
    {
        $build = [
            'application' => $_SERVER['BITRISE_APP_TITLE'],
            'build_version' => $_SERVER['BUNDLE_VERSION'],
            'build_number' => $_SERVER['BITRISE_BUILD_NUMBER'],
            'built_on' => $_SERVER['BITRISE_BUILD_TRIGGER_DATE'],
            'git_branch' => $_SERVER['BITRISE_GIT_BRANCH'],
            'actions' => "<a href=\"$_SERVER[APPETIZE_APP_URL]\" class=\"external-link\" rel=\"nofollow\">Test</a> |
<a href=\"$_SERVER[BITRISE_PUBLIC_INSTALL_PAGE_URL]\" class=\"external-link\" rel=\"nofollow\">Download</a>
(<a href=\"$_SERVER[BITRISE_PUBLIC_INSTALL_PAGE_QR_CODE_IMAGE_URL_ESCAPED]\" class=\"external-link\" rel=\"nofollow\">QR</a>)",
        ];

        $fromBuilds[] = $build;

        return $fromBuilds;
    }
}
