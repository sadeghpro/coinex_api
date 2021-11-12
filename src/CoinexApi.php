<?php

namespace Coinex;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CoinexApi
{
    private $id;
    private $key;

    public function __construct()
    {
        $this->id = env('COINEX_ID');
        $this->key = env('COINEX_KEY');
    }

    private function createAuthorization($params): string
    {
        ksort($params);
        return strtoupper(md5(http_build_query($params) . '&secret_key=' . $this->key));
    }

    public function getBalance() : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000
        ];
        $url = "https://api.coinex.com/v1/balance/info";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->get($url, $params);
    }

    public function getWithdrawalList() : Response
    {
        // TODO must add other params to filter and also add paging
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
        ];
        $url = "https://api.coinex.com/v1/balance/coin/withdraw";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->get($url, $params);
    }

    public function getMarketFee($market, $business_type = 'SPOT') : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
            "market" => $market,
            "business_type" => $business_type,
        ];
        $url = "https://api.coinex.com/v1/account/market/fee";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->get($url, $params);
    }

    public function getWalletAddress($coin, $protocol = '') : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
        ];
        if ($protocol) {
            $params['smart_contract_name'] = $protocol;
        }
        $url = "https://api.coinex.com/v1/balance/deposit/address/$coin";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->get($url, $params);
    }

    public function createWallet($coin, $protocol = '') : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
        ];
        if ($protocol) {
            $params['smart_contract_name'] = $protocol;
        }
        $url = "https://api.coinex.com/v1/balance/deposit/address/$coin";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->put($url, $params);
    }

    public function withdraw($coin, $address, $amount, $protocol = '') : Response
    {
        // TODO must check
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
            "coin_type" => $coin,
            "coin_address" => $address,
            "transfer_method" => "onchain",
            "actual_amount" => $amount,
        ];
        if ($protocol) {
            $params['smart_contract_name'] = $protocol;
        }
        $url = "https://api.coinex.com/v1/balance/coin/withdraw";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->post($url, $params);
    }

    public function createLimitOrder($market, $type, $amount, $price) : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
            "market" => $market,
            "type" => $type,
            "price" => $price,
            "amount" => $amount,
        ];
        $url = "https://api.coinex.com/v1/order/limit";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->post($url, $params);
    }

    public function createMarketOrder($market, $type, $amount) : Response
    {
        // TODO must test
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
            "market" => $market,
            "type" => $type,
            "amount" => $amount,
        ];
        $url = "https://api.coinex.com/v1/order/market";
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->post($url, $params);
    }

    public function cancelOrder($id, $market, $type) : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
            "id" => $id,
            "market" => $market,
            "type" => $type,
        ];
        $url = "https://api.coinex.com/v1/order/pending?" . http_build_query($params);
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->delete($url);
    }

    public function getOrders($page = 1, $market = '', $limit = 100, $type = '') : Response
    {
        $params = [
            "access_id" => $this->id,
            "tonce" => time() * 1000,
            "page" => $page,
            "limit" => $limit,
        ];
        if ($market) {
            $params['market'] = $market;
        }
        if ($type) {
            $params['type'] = $type;
        }
        $url = "https://api.coinex.com/v1/order/pending?" . http_build_query($params);
        return Http::withHeaders([
            "authorization" => $this->createAuthorization($params)
        ])->get($url);
    }


    public function getMarketList() : Response
    {
        $url = "https://api.coinex.com/v1/market/list";
        return Http::get($url);
    }

    public function getMarketInfoList() : Response
    {
        $url = "https://api.coinex.com/v1/market/info";
        return Http::get($url);
    }

    public function getMarketData($market) : Response
    {
        $params = [
            "market" => $market
        ];
        $url = "https://api.coinex.com/v1/market/ticker";
        return Http::get($url, $params);
    }

    public function getMarketInfo($market) : Response
    {
        $params = [
            "market" => $market
        ];
        $url = "https://api.coinex.com/v1/market/detail";
        return Http::get($url, $params);
    }


}
