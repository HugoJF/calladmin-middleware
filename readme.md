# Calladmin Middleware

Centralized report system integrated with CallAdmin and SourceBans. CallAdmin Middleware (CAM) main objetive is to allow community members to help decide (using votes) and discuss reports. It's also a good way to find reliable admins that have good report/vote accuracy.

## How it works

CAM relies on SourceMod [plugin](https://github.com/HugoJF/calladmin-reports) to record a demo and upload it via an API. CAM will store the demo (we currently use Minio as a S3 compatible storage) so users are able to easily download the demo.

A few features:
  - Demo download;
  - Demo chat;
  - Demo player list;
  - Report comment area;
  - 3 different user scores (target score, reporter score, vote score) to help admins decide;
  - Demo video recording.

Any user can vote on a report. Votes are only used as an indicator, and they are not used to decide reports. Only users marked as admin are able to decide a report and possibly apply a ban. CAM also relies on SourceBans to handle bans, and since it does not have an API, we directly interact with it's database.

Once a report is created, decided or voted, or a comment is created, admins are notified using our notification system (which can also send emails).

As a way to punish bad reports, users with reports decided as incorrect, are required to acknowledge their mistake (until then, users with incorrect reports are kicked from the servers, with an ACK URL).

## Screenshots

#### Report view 

<p align="center">
  <img src="https://i.imgur.com/9ObhDL4.png">
</p>

## Requirements
  - PHP 7.x
  - MySQL/MariaDB
  - CS:GO server with [calladmin-reports](https://github.com/HugoJF/calladmin-reports) and CallAdmin
  - Installation of [csgo-server-api](https://github.com/HugoJF/csgo-server-api)
  - SourceBans
  - (optional) [csgo-demo-recorder](https://github.com/HugoJF/csgo-demo-recorder)

## Installation
  - Deploy this app;
  - Setup .env file;
  - Setup [calladmin-reports](https://github.com/HugoJF/calladmin-reports) on each server;
  - Setup storage;
