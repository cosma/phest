<?php/**
 * This file is part of the "cosma/phest" project
 *
 * (c) Cosmin Voicu<cosmin.voicu@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Cosma\Phest\Http;

use \Phalcon\Http\Response as OverwrittenResponse;

class Response extends OverwrittenResponse
{
    /**
     * @return $this
     */
    public function send()
    {
        return $this;
    }
}