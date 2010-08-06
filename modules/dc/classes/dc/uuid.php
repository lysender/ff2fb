<?php defined('SYSPATH') or die('No direct script access.');

/**
 * DrUUID RFC4122 library for PHP5
 *  by J. King (http://jkingweb.ca/)
 * Licensed under MIT license
 *
 * See http://jkingweb.ca/code/php/lib.uuid/
 *  for documentation
 *
 * Last revised 2010-02-15
 */

/**
 * Copyright (c) 2009 J. King
 * 
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 * 
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */


class Dc_Uuid
{
    const MD5  = 3;
    const SHA1 = 5;
    
    const clearVer = 15;  // 00001111  Clears all bits of version byte with AND
    const clearVar = 63;  // 00111111  Clears all relevant bits of variant byte with AND
    const varRes   = 224; // 11100000  Variant reserved for future use
    const varMS    = 192; // 11000000  Microsft GUID variant
    const varRFC   = 128; // 10000000  The RFC 4122 variant (this variant)
    const varNCS   = 0;   // 00000000  The NCS compatibility variant
    const version1 = 16;  // 00010000
    const version3 = 48;  // 00110000
    const version4 = 64;  // 01000000
    const version5 = 80;  // 01010000
    const interval = 0x01b21dd213814000; // Time (in 100ns steps) between the start of the UTC and Unix epochs
    
    const nsDNS  = '6ba7b810-9dad-11d1-80b4-00c04fd430c8';
    const nsURL  = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    const nsOID  = '6ba7b812-9dad-11d1-80b4-00c04fd430c8';
    const nsX500 = '6ba7b814-9dad-11d1-80b4-00c04fd430c8';
    
    protected static $randomFunc = 'randomTwister';
    protected static $randomSource = null;
    //instance properties
    protected $bytes;
    protected $hex;
    protected $string;
    protected $urn;
    protected $version;
    protected $variant;
    protected $node;
    protected $time;
 
    /**
     * Generates UUID
     *
     * @param int $ver
     * @param mixed $node
     * @param mixed $ns
     * @return Dc_Uuid
     */
    public static function mint($ver = 1, $node = null, $ns = null)
    {
        // Create a new UUID based on provided data.
        switch((int) $ver)
        {
            case 1:
                return new self(self::mintTime($node));
            case 2:
                // Version 2 is not supported 
                throw new Exception("Version 2 is unsupported.");
            case 3:
                return new self(self::mintName(self::MD5, $node, $ns));
            case 4:
                return new self(self::mintRand());
            case 5:
                return new self(self::mintName(self::SHA1, $node, $ns));
            default:
                throw new Exception("Selected version is invalid or unsupported.");
        }
    }

    /**
     * Imports an existing UUID
     *
     * @param mixed $uuid
     * @return Dc_Uuid
     */
    public static function import($uuid)
    {
        return new self(self::makeBin($uuid, 16));
    }

    /**
     * Compares the binary representations of two UUIDs.
     * The comparison will return true if they are bit-exact,
     * or if neither is valid
     */
    public static function compare($a, $b)
    {
        if (self::makeBin($a, 16) == self::makeBin($b, 16))
        {
            return true;
        }
        return false;
    }

    public function __toString()
    {
        return $this->string;
    }

    public function __get($var)
    {
        switch ($var)
        {
            case "bytes":
                return $this->bytes;
            case "hex":
                return bin2hex($this->bytes);
            case "string":
                return $this->__toString();
            case "urn":
                return "urn:uuid:".$this->__toString();
            case "version":
                return ord($this->bytes[6]) >> 4;
            case "variant":
                $byte = ord($this->bytes[8]);
                if ($byte >= self::varRes)
                {
                    return 3;
                }
                if ($byte >= self::varMS)
                {
                    return 2;
                }
                if ($byte >= self::varRFC)
                {
                    return 1;
                }
                else
                {
                    return 0;
                }
            case "node":
                if (ord($this->bytes[6])>>4==1)
                {
                    return bin2hex(substr($this->bytes,10));
                }
                else
                {
                    return null; 
                }
            case "time":
                if (ord($this->bytes[6])>>4==1)
                {
                    // Restore contiguous big-endian byte order
                    $time = bin2hex($this->bytes[6].$this->bytes[7].$this->bytes[4].$this->bytes[5].$this->bytes[0].$this->bytes[1].$this->bytes[2].$this->bytes[3]);
                    // Clear version flag
                    $time[0] = "0"; 
                    // Do some reverse arithmetic to get a Unix timestamp
                    $time = (hexdec($time) - self::interval) / 10000000;
                    return $time;
                }
                else
                {
                    return null;
                }
            default:
                return null;
        }
    }

    protected function __construct($uuid) {
        if (strlen($uuid) != 16)
        {
            throw new Exception("Input must be a 128-bit integer.");
        }
        
        $this->bytes  = $uuid;
        // Optimize the most common use
        $this->string = 
            bin2hex(substr($uuid,0,4))."-".
            bin2hex(substr($uuid,4,2))."-".
            bin2hex(substr($uuid,6,2))."-".
            bin2hex(substr($uuid,8,2))."-".
            bin2hex(substr($uuid,10,6));
    }

    /**
     * Generates a Version 1 UUID
     * These are derived from the time at which they were generated
     */
    protected static function mintTime($node = null)
    {
        // Get time since Gregorian calendar reform in 100ns intervals
        // This is exceedingly difficult because of PHP's (and pack()'s) 
        //  integer size limits.
        // Note that this will never be more accurate than to the microsecond.
        $time = microtime(1) * 10000000 + self::interval;
        
        // Convert to a string representation
        $time = sprintf("%F", $time);
        preg_match("/^\d+/", $time, $time); //strip decimal point
        
        // And now to a 64-bit binary representation
        $time = base_convert($time[0], 10, 16);
        $time = pack("H*", str_pad($time, 16, "0", STR_PAD_LEFT));
        
        // Reorder bytes to their proper locations in the UUID
        $uuid  = $time[4].$time[5].$time[6].$time[7].$time[2].$time[3].$time[0].$time[1];
        
        // Generate a random clock sequence
        $uuid .= self::randomBytes(2);
        
        // set variant
        $uuid[8] = chr(ord($uuid[8]) & self::clearVar | self::varRFC);
        
        // set version
        $uuid[6] = chr(ord($uuid[6]) & self::clearVer | self::version1);
        
        // Set the final 'node' parameter, a MAC address
        if ($node)
        {
            $node = self::makeBin($node, 6);
        }
        if (!$node)
        { 
            // If no node was provided or if the node was invalid, 
            //  generate a random MAC address and set the multicast bit
            $node = self::randomBytes(6);
            $node[0] = pack("C", ord($node[0]) | 1);
        }
        
        $uuid .= $node;
        return $uuid;
    }

    /**
     * Generate a Version 4 UUID.  
     * These are derived soly from random numbers.
     */
    protected static function mintRand()
    {
        // generate random fields
        $uuid = self::randomBytes(16);
        // set variant
        $uuid[8] = chr(ord($uuid[8]) & self::clearVar | self::varRFC);
        // set version
        $uuid[6] = chr(ord($uuid[6]) & self::clearVer | self::version4);
        return $uuid;
    }

    /**
     * Generates a Version 3 or Version 5 UUID.
     * These are derived from a hash of a name and its namespace, in binary form.
     */
    protected static function mintName($ver, $node, $ns)
    {
        if (!$node)
        {
            throw new Exception("A name-string is required for Version 3 or 5 UUIDs.");
        }
        
        // if the namespace UUID isn't binary, make it so
        $ns = self::makeBin($ns, 16);
        if (!$ns)
        {
            throw new UUIDException("A binary namespace is required for Version 3 or 5 UUIDs.");
        }
        
        switch ($ver)
        {
            case self::MD5: 
                $version = self::version3;
                $uuid = md5($ns.$node,1);
                break;
            case self::SHA1:
                $version = self::version5;
                $uuid = substr(sha1($ns.$node,1),0, 16);
                break;
        }
        // set variant
        $uuid[8] = chr(ord($uuid[8]) & self::clearVar | self::varRFC);
        // set version
        $uuid[6] = chr(ord($uuid[6]) & self::clearVer | $version);
        return ($uuid);
    }

    /**
     * Insure that an input string is either binary or hexadecimal.
     * Returns binary representation, or false on failure.
     */
    protected static function makeBin($str, $len)
    {
        if ($str instanceof self)
        {
            return $str->bytes;
        }
        if (strlen($str)==$len)
        {
            return $str;
        }
        else
        {
            $str = preg_replace("/^urn:uuid:/is", "", $str); // strip URN scheme and namespace
            $str = preg_replace("/[^a-f0-9]/is", "", $str);  // strip non-hex characters
            if (strlen($str) != ($len * 2))
            {
                return false;
            }
            else
            {
                return pack("H*", $str);
            }
        }
    }

    /**
     * Look for a system-provided source of randomness,
     * which is usually crytographically secure.
     * /dev/urandom is tried first simply out of bias for Linux systems.
     */
    public static function initRandom()
    {
        if (is_readable('/dev/urandom'))
        {
            self::$randomSource = fopen('/dev/urandom', 'rb');
            self::$randomFunc = 'randomFRead';
        }
        else if (class_exists('COM', 0))
        {
            try {
                self::$randomSource = new COM('CAPICOM.Utilities.1');  // See http://msdn.microsoft.com/en-us/library/aa388182(VS.85).aspx
                self::$randomFunc = 'randomCOM';
            }
            catch(Exception $e) {
                
            }
        }
        return self::$randomFunc;
    } 

    public static function randomBytes($bytes)
    {
        return call_user_func(array('self', self::$randomFunc), $bytes);
    } 

    /**
     * Get the specified number of random bytes, using mt_rand().
     * Randomness is returned as a string of bytes.
     */
    protected static function randomTwister($bytes)
    {
        $rand = "";
        for ($a = 0; $a < $bytes; $a++)
        {
            $rand .= chr(mt_rand(0, 255));
        } 
        return $rand;
    }
 
    /**
     * Get the specified number of random bytes using a file handle
     * previously opened with UUID::initRandom().
     * Randomness is returned as a string of bytes
     */
    protected static function randomFRead($bytes)
    {
        return fread(self::$randomSource, $bytes);
    }
 
    /**
     * Get the specified number of random bytes using Windows
     * randomness source via a COM object previously created by UUID::initRandom()
     * Randomness is returned as a string of bytes
     */
    protected static function randomCOM($bytes)
    {
        return base64_decode(self::$randomSource->GetRandom($bytes,0)); // straight binary mysteriously doesn't work, hence the base64
    }
    
    /**
     * Returns UUID object if a string is a valid uuid
     * Returns false when not a valid uuid
     *
     * @param string $str
     * @return Dc_Uuid $object
     */
    public static function stringUuid($str)
    {
        try {
            $id = self::import($str);
            return $id;
        }
        catch (Exception $e)
        {
            
        }
        
        return false;
    }
    
    /**
     * Returns true if and only if the string
     * is a valid uuid string
     */
    public static function isValidUuid($str)
    {
        return (boolean)self::stringUuid($str);
    }
    
    /**
     * Converts all binary ids to string for a given
     * array input where it could be a single dimensional array
     * or a 2d array
     *
     * @param array $input 1d or 2d array
     * @param array $fields fields to convert ids
     * @param boolean $multi 2d if true
     * @param boolean $noDash removes dashes if true
     * 
     * @return array
     */
    public static function normalizedIds(array $input, array $fields, $multi = false, $noDash = false)
    {
        $result = array();
        if ($multi)
        {
            foreach ($input as $in)
            {
                $result[] = self::_normalizeIds($in, $fields, $noDash);
            }
            return $result;
        }
        else
        {
            return self::_normalizeIds($input, $fields, $noDash);
        }
    }
    
    protected static function _normalizeIds(array $input, array $fields, $noDash = false)
    {
        foreach ($fields as $field)
        {
            $id = self::import($input[$field])->string;
            if ($noDash)
            {
                $id = str_replace('-', '', $id);
            }
            $input[$field] = $id;
        }
        
        return $input;
    }
}

