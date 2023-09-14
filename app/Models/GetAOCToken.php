<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GetAOCToken extends Model
{
    use HasFactory;

    protected $fillable = [
         'apiKey',
         'username',
         'spTransID',
         'description',
         'currency',
         'amount',
         'onBehalfOf',
         'purchaseCategoryCode',
         'refundPurchaseCategoryCode',
         'referenceCode',
         'channel',
         'operator',
         'taxAmount',
         'callbackURL',
         'contactInfo',
         'contentURL',
         'isSubscription',
         'subscriptionID',
         'subscriptionName',
         'subscriptionDuration',
         'unSubURL',
         'isWallet',
         'isWebDeepLink',
         'callbackAppDeepLink',
         'msisdn',
         'email',
         'isMobileAppAPI',
         'tacMSISDN',
         'productId',
         'renewalCharge',
         'requireOTP',
    ];
}
