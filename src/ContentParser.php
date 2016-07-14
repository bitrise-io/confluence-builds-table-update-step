<?php
namespace DAG\JIRA\BuildsTable;

/**
 * Class ContentParser
 */
final class ContentParser
{
    public function parse($content, $variables)
    {
        $config = [];

        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $groups = [];
            if (preg_match('@([\w]+):(.+)@', $line, $groups)) {
                $configName = trim($groups[1]);
                $configValue = trim($groups[2]);

                $configValue = preg_replace_callback(
                    '@\$([\w]+)@',
                    function ($matches) use ($variables) {
                        $variableName = $matches[1];
                        if (isset($variables[$variableName])) {
                            return $variables[$variableName];
                        }

                        return '$'.$variableName;
                    },
                    $configValue
                );

                $config[$configName] = $configValue;
            }
        }

        return $config;
    }
}
