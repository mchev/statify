<?php

namespace App\Http\Middleware;

use App\Models\Website;
use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        try {
            $domains = Website::pluck('domain')->toArray();
        } catch (QueryException $e) {
            $domains = [];
        }
        $trustedHosts = [$this->allSubdomainsOfApplicationUrl()];
        $trustedHosts = array_merge($trustedHosts, $domains);

        return $trustedHosts;
    }
}
