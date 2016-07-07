#!/bin/bash

THIS_SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

source "${THIS_SCRIPTDIR}/_bash_utils/utils.sh"
source "${THIS_SCRIPTDIR}/_bash_utils/formatted_output.sh"

# init / cleanup the formatted output
echo "" > "${formatted_output_file_path}"

if [ -z "${page_id}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$page_id` not provided!'
	exit 1
fi

if [ -z "${jira_user}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_user` not provided!'
	exit 1
fi

if [ -z "${jira_password}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_password` not provided!'
	exit 1
fi

if [ -z "${cells}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$cells` not provided!'
	exit 1
fi

if [ -z "${jira_url}" ] ; then
	write_section_to_formatted_output "# Error"
	write_section_start_to_formatted_output '* Required input `$jira_url` not provided!'
	exit 1
fi

# Install dependencies
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=$THIS_SCRIPTDIR
php "${THIS_SCRIPTDIR}/composer.phar install"

# Run script
resp=$(php "${THIS_SCRIPTDIR}/application.php")
ex_code=$?

if [ ${ex_code} -eq 0 ] ; then
	echo "${resp}"
	write_section_to_formatted_output "# Success"
	echo_string_to_formatted_output "Table updated."
	exit 0
fi

write_section_to_formatted_output "# Error"
write_section_to_formatted_output "Updating table failed with the following error:"
echo_string_to_formatted_output "${resp}"
exit 1
