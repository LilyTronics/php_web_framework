"""
Check the web access for your web site.
Copy this file to the appication/scripts folder in your project.

Requires requests: pip install requests
"""

import os
import re
import requests


# Host URI, e.g.: http://localhost/my_project
HOST_URI = "http://localhost"

# All not accessible files must return status code 403 (forbidden).
# Define here your exeptions using regular expressions
EXCEPTIONS = {
    # "reg_expres" : expected_status_code
    r"index.php": 200,
    r"php_framework/index.php": 404
}

# Script must be in application/scripts, to make this work
ROOT_PATH = os.path.abspath(os.path.join(os.path.dirname(__file__), "../.."))

# Check files for accessibility
for current_folder, sub_folders, filenames in os.walk(ROOT_PATH):
    sub_folders.sort()
    for filename in filenames:
        rel_path = current_folder[len(ROOT_PATH) + 1:].replace("\\", "/")
        rel_path = os.path.join(rel_path, filename).replace("\\", "/")
        uri = f"{HOST_URI}/{rel_path}"
        # All requests must return 403 for files that are not accessible.
        # Others must be defined in the exception list
        expected_code = 403
        for regex in EXCEPTIONS:
            matches = re.findall(regex, rel_path)
            if len(matches) == 1:
                expected_code = EXCEPTIONS[regex]
        r = requests.get(uri)
        if r.status_code != expected_code:
            print(f"WARNING: {uri} returned status code: {r.status_code}, expected: {expected_code}")
