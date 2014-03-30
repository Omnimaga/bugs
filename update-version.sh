#!/bin/bash
date > data/version;
sed -i -e "s/Version .*/Version $(date)/g" bugs.appcache;
git add bugs.appcache;