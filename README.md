# BasIp minimal api client

Usage:

```php
# Instance, auth
$basIp = new \AlexMorbo\BasIpApiClient\BasIp('10.0.0.1');
$basIp->setCredentials('admin', '123456');

# ------------------
# Methods (api v1):
# ------------------

# Returns Login Dto, not necessary, works automatically inside BasIp magic calls
$basIp->login($login, $password);

# [GET]  api/info
$basIp->getInfo();
# [GET]  api/v1/device/language
$basIp->getDeviceLanguage();
# [POST] api/v1/device/language
$basIp->setDeviceLanguage('English');
# [GET]  api/v1/sip/status
$basIp->getSipStatus();
# [GET]  api/v1/device/time
$basIp->getDeviceTime();

# [GET]  api/v1/network/settings
$basIp->getNetworkSettings();
# [GET]  api/v1/network/mac
$basIp->getNetworkMac();
# [GET]  api/v1/network/ntp
$basIp->getNetworkNtp();

# [GET]  api/v1/access/general/unlock/input/code
$basIp->getAccessInputCode();
# [GET]  api/v1/access/general/lock/open/remote/accepted/0
$basIp->openLock(0);

# [GET]  api/v1/photo/file
$basIp->getCameraSnapshot();
```