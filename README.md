# rttchatroom
This is a simple php/javascript implementation of a realtime-text chat room.

Installation
============
Just clone the repository into a folder reachable from the internet. Optionally customize the temp path in cfg.php (this folder contains room histories).

i.e.:
- Install Apache and PHP or another server solution.
- Copy the files into your web root (or another folder).
- Edit the tmp path in the cfg.php. .
- Make sure that the temporary path is cleared from time to time.
- Make sure that the tail -f command is available. (Should be available by default on Unix systems.)

Implementation
==============
There are three important files:
- chat.php - contains all functionality. I.e.:
  - the chat interface
  - textarea which sends updates to the postmsg.php script on the server
  - handler which reads incremental updates from the stream.php and either updates the message or creates a new one
- stream.php - provides stream of changes from the room history file.
- postmsg.php - accepts message updates and appends them into the history file.

