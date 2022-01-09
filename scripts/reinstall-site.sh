#!/usr/bin/env bash

set -ex

drush si --no-interaction \
  && drush entity:delete shortcut_set --no-interaction \
  && drush cset system.site uuid "f688a2b4-7b54-4483-9106-1c8500b879b1" --no-interaction \
  && drush config:import --no-interaction