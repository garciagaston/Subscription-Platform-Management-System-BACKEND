<?php

namespace App\Helpers;

use App\Models\Channel;
use App\Models\Package;
use Exception;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;

final class PackageHelper
{
    public static function attachChannelToPackage(Package $package, Channel $channel): void
    {
        if ($package->channels->contains($channel->id)) {
            throw new Exception("The package with ID #{$package->id} is already associated with the channel that has ID #{$channel->id}.");
        }
        $channelsOld = $package->channels->pluck('id')->toArray();
        $package->channels()->syncWithoutDetaching($channel);
        $package->refresh();
        $channelsNew = $package->channels->pluck('id')->toArray();
        $package->auditEvent = 'package-channels-attach';
        $package->isCustomEvent = true;
        $package->auditCustomOld = [
            'channels_in_package' => $channelsOld
        ];
        $package->auditCustomNew = [
            'channels_in_package' => $channelsNew
        ];
        Event::dispatch(AuditCustom::class, [$package]);
    }

    public static function detachChannelToPackage(Package $package, Channel $channel): void
    {
        if (!$package->channels->contains($channel->id)) {
            throw new Exception("The package with ID #{$package->id} is not associated with the channel that has ID #{$channel->id}.");
        }
        $channelsOld = $package->channels->pluck('id')->toArray();
        $package->channels()->detach($channel);
        $package->refresh();
        $channelsNew = $package->channels->pluck('id')->toArray();
        $package->auditEvent = 'package-channels-detach';
        $package->isCustomEvent = true;
        $package->auditCustomOld = [
            'channels_in_package' => $channelsOld
        ];
        $package->auditCustomNew = [
            'channels_in_package' => $channelsNew
        ];
        Event::dispatch(AuditCustom::class, [$package]);
    }
}
