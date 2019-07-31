<?php
DEFINE("ENCODING_EAN128", 1);
DEFINE("ENCODING_EAN13", 2);
DEFINE("ENCODING_EAN8", 3);
DEFINE("ENCODING_UPCA", 4);
DEFINE("ENCODING_UPCE", 5);
DEFINE("ENCODING_CODE39", 6);
DEFINE("ENCODING_CODE93", 7);
DEFINE("ENCODING_CODE128", 8);
DEFINE("ENCODING_POSTNET", 9);
DEFINE("ENCODING_BOOKLAND", 10);
DEFINE("ENCODING_CODE25", 11);
DEFINE("ENCODING_CODEI25", 12);
DEFINE("ENCODING_CODABAR", 13);
DEFINE("ENCODING_CODE11", 14);
DEFINE("BACKEND_IMAGE", 'IMAGE');
DEFINE("BACKEND_PS", 'PS');
DEFINE("ADD_DEMOTXT", false);

class BarcodeFactory
{
    function Create($aEncoder)
    {
        $names = array(
            ENCODING_EAN128,
            "EAN128",
            ENCODING_EAN13,
            "EAN13",
            ENCODING_EAN8,
            "EAN8",
            ENCODING_UPCA,
            "UPCA",
            ENCODING_UPCE,
            "UPCE",
            ENCODING_CODE39,
            "CODE39",
            ENCODING_CODE93,
            "CODE93",
            ENCODING_CODE128,
            "CODE128",
            ENCODING_POSTNET,
            "POSTNET",
            ENCODING_BOOKLAND,
            "BOOKLAND",
            ENCODING_CODE25,
            "CODE25",
            ENCODING_CODEI25,
            "CODEI25",
            ENCODING_CODABAR,
            "CODABAR",
            ENCODING_CODE11,
            "CODE11"
        );
        $pos = array_search($aEncoder, $names);
        if ($pos === false) {
            JpGraphError::RaiseL(1001, $aEncoder);
        }
        $name = 'BarcodeEncode_' . $names[$pos + 1];
        $obj = new $name();
        return $obj;
    }
}

class BarcodeEncode
{
    var $iName = 'UNDEFINED';
    var $iUseChecksum = false;
    var $iUseTilde = false;

    function BarcodeEncode()
    {
    }

    function Enc($aData)
    {
        if ($this->iUseTilde) {
            $aData = $this->TildeProcess($aData);
        }
        if (!$this->Validate($aData, false)) {
            JpGraphError::RaiseL(1002, $aData, $this->GetName());
        }
    }

    function SetTilde($aFlg = true)
    {
        $this->iUseTilde = $aFlg;
    }

    function GetName()
    {
        return $this->iName;
    }

    function AddChecksum($aFlg = true)
    {
        $this->iUseChecksum = $aFlg;
    }

    function DoValidate($aData, $aErrMsg = true)
    {
        $r = $this->Validate($aData);
        if ($aErrMsg && !$r) {
            JpGraphError::RaiseL(1002, $aData, $this->GetName());
            exit(1);
        }
        return $r;
    }

    function TildeProcess($aStr)
    {
        $r = str_replace('~@', chr(0), $aStr);
        for ($i = 0; $i < 26; ++$i) {
            $r = str_replace('~' . chr($i + 65), chr($i + 1), $r);
        }
        $offset = 0;
        while (($n = strpos($r, '~d', $offset)) !== false) {
            $sv = substr($r, $n + 2, 3);
            if (strlen($sv) < 3 || !ctype_digit($sv)) {
                return false;
            }
            $v = intval($sv);
            $r = str_replace('~d' . $sv, chr($v), $r);
            $offset = $n + 1;
        }
        return $r;
    }

    function CalcChecksum($aData, $aOdd, $aOddMult, $aEven, $aEvenMult, $aMod)
    {
        $no = count($aOdd);
        $ne = count($aEven);
        $sum = 0;
        for ($i = 0; $i < $no; ++$i) {
            $c = ($aData[$aOdd[$i]] - '0');
            $sum += $c;
        }
        $sum *= $aOddMult;
        $sum2 = 0;
        for ($i = 0; $i < $ne; ++$i) {
            $sum2 += ($aData[$aEven[$i]] - '0');
        }
        $sum2 *= $aEvenMult;
        $sum += $sum2;
        $cn = $sum % $aMod;
        if ($cn == 0) {
            return 0;
        } else {
            return 10 - $cn;
        }
    }
}

class BarcodeEncode_EAN13 extends BarcodeEncode
{
    var $iParity = array(
        '00000',
        '001011',
        '001101',
        '001110',
        '010011',
        '011001',
        '011100',
        '010101',
        '010110',
        '011010'
    );
    var $iSymbols = array(
        array('3211', '2221', '2122', '1411', '1132', '1231', '1114', '1312', '1213', '3112'),
        array('1123', '1222', '2212', '1141', '2311', '1321', '4111', '2131', '3121', '2113')
    );
    var $iGuards = array('111', '11111', '111');

    function BarcodeEncode_EAN13()
    {
        parent::BarcodeEncode();
        $this->iName = 'EAN-13';
    }

    function Enc($aData)
    {
        return $this->_Enc($aData, false);
    }

    function EncUPCA($aData)
    {
        return $this->_Enc($aData, true);
    }

    function _Enc($aData, $aUPCA = false)
    {
        parent::Enc($aData);
        $cn = $this->GetChecksum($aData);
        $aData .= $cn;
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = $aData;
        $e->iLeftData = substr($aData, 0, 1);
        $e->iBar = array();
        $e->iBar[] = array(0, 1, 1, $this->iGuards[0]);
        if (!$aUPCA) {
            $p = $this->iParity[substr($aData, 0, 1) + 0];
        } else {
            $p = $this->iParity[0];
        }
        $e->iInfo = "Parity=$p";
        if ($aUPCA) {
            $e->iRightData = substr($aData, 11, 1);
            for ($i = 0; $i < 6; ++$i) {
                $pi = substr($p, $i, 1) + 0;
                $c = substr($aData, $i, 1);
                if ($i > 0) {
                    $e->iBar[] = array($c, 0, 0, $this->iSymbols[$pi][$c - '0']);
                } else {
                    $e->iBar[] = array($c, 0, 1, $this->iSymbols[$pi][$c - '0']);
                }
            }
            $e->iBar[] = array(0, 0, 1, $this->iGuards[1]);
            for ($i = 0; $i < 6; ++$i) {
                $c = substr($aData, $i + 6, 1);
                if ($i < 5) {
                    $e->iBar[] = array($c, 1, 0, $this->iSymbols[0][$c - '0']);
                } else {
                    $e->iBar[] = array($c, 1, 1, $this->iSymbols[0][$c - '0']);
                }
            }
        } else {
            for ($i = 0; $i < 6; ++$i) {
                $pi = substr($p, $i, 1) + 0;
                $c = substr($aData, $i + 1, 1);
                $e->iBar[] = array($c, 0, 0, $this->iSymbols[$pi][$c - '0']);
            }
            $e->iBar[] = array(0, 0, 1, $this->iGuards[1]);
            for ($i = 0; $i < 6; ++$i) {
                $c = substr($aData, $i + 7, 1);
                $e->iBar[] = array($c, 1, 0, $this->iSymbols[0][$c - '0']);
            }
        }
        $e->iBar[] = array(0, 1, 1, $this->iGuards[2]);
        return $e;
    }

    function Validate($aData)
    {
        $n = strlen($aData);
        if ($n > 12 || $n < 12) {
            return false;
        }
        return ctype_digit($aData);
    }

    function GetChecksum($aData)
    {
        $n = $this->CalcChecksum($aData, array(1, 3, 5, 7, 9, 11), 3, array(0, 2, 4, 6, 8, 10), 1, 10);
        return $n;
    }
}

class BarcodeEncode_EAN8 extends BarcodeEncode_EAN13
{
    function BarcodeEncode_EAN8()
    {
        parent::BarcodeEncode();
        $this->iName = 'EAN-8';
    }

    function Enc($aData)
    {
        if (!$this->Validate($aData, false)) {
            JpGraphError::RaiseL(1002, $aData, $this->GetName());
        }
        $cn = $this->GetChecksum($aData);
        $aData .= $cn;
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = $aData;
        $e->iBar[0] = array(0, 1, 1, $this->iGuards[0]);
        for ($i = 0; $i < 4; ++$i) {
            $c = substr($aData, $i, 1);
            $e->iBar[$i + 1] = array($c, 0, 0, $this->iSymbols[0][$c - '0']);
        }
        $e->iBar[5] = array(0, 0, 1, $this->iGuards[1]);
        for ($i = 0; $i < 4; ++$i) {
            $c = substr($aData, $i + 4, 1);
            $e->iBar[$i + 6] = array($c, 1, 0, $this->iSymbols[0][$c - '0']);
        }
        $e->iBar[10] = array(0, 1, 1, $this->iGuards[0]);
        return $e;
    }

    function Validate($aData)
    {
        return strlen($aData) <= 7 && ctype_digit($aData);
    }

    function GetChecksum($aData)
    {
        $n = $this->CalcChecksum($aData, array(0, 2, 4, 6), 3, array(1, 3, 5), 1, 10);
        return $n;
    }
}

class BarcodeEncode_UPCA extends BarcodeEncode_EAN13
{
    function BarcodeEncode_UPCA()
    {
        parent::BarcodeEncode();
        $this->iName = 'EAN-UPCA';
    }

    function Enc($aData)
    {
        return parent::EncUPCA($aData);
    }

    function Validate($aData)
    {
        return strlen($aData) <= 11 && parent::Validate("0" . $aData);
    }

    function GetChecksum($aData)
    {
        $n = $this->CalcChecksum($aData, array(0, 2, 4, 6, 8, 10), 3, array(1, 3, 5, 7, 9), 1, 10);
        return $n;
    }
}

class BarcodeEncode_UPCE extends BarcodeEncode
{
    var $iParity = array(
        '000111',
        '001011',
        '001101',
        '001110',
        '010011',
        '011001',
        '011100',
        '010101',
        '010110',
        '011010'
    );
    var $iSymbols = array(
        array('1123', '1222', '2212', '1141', '2311', '1321', '4111', '2131', '3121', '2113'),
        array('3211', '2221', '2122', '1411', '1132', '1231', '1114', '1312', '1213', '3112')
    );
    var $iGuards = array('111', '111111');
    var $iType = -1;
    var $iOrder = array(
        array(1, 2, 8, 9, 10, 3),
        array(1, 2, 3, 9, 10),
        array(1, 2, 3, 4, 10),
        array(1, 2, 3, 4, 5, 10)
    );

    function BarcodeEncode_UPCE()
    {
        parent::BarcodeEncode();
        $this->iName = 'UPC-E';
    }

    function Enc($aData)
    {
        parent::Enc($aData);
        $cn = $this->GetChecksum($aData);
        $aData .= $cn;
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = $aData;
        $e->iLeftData = substr($aData, 0, 1);
        $e->iRightData = substr($aData, 11, 1);
        $ns = substr($aData, 0, 1) + 0;
        $p = $this->iParity[$cn + 0];
        $e->iInfo = " Parity=$p, Type=$this->iType";
        $e->iBar[0] = array(0, 1, 1, $this->iGuards[0]);
        $n = count($this->iOrder[$this->iType]);
        for ($i = 0; $i < $n; ++$i) {
            $c = substr($aData, $this->iOrder[$this->iType][$i], 1);
            $pi = $ns == 0 ? substr($p, $i, 1) + 0 : 1 - substr($p, $i, 1);
            $e->iBar[$i + 1] = array($c, 0, 0, $this->iSymbols[$pi][$c + 0]);
        }
        switch ($this->iType) {
            case 0:
            case 3:
                break;
            case 1:
                $pi = $ns == 0 ? substr($p, 5, 1) + 0 : 1 - substr($p, 5, 1);
                $e->iBar[6] = array('3', 0, 0, $this->iSymbols[$pi][3]);
                break;
            case 2:
                $pi = $ns == 0 ? substr($p, 5, 1) + 0 : 1 - substr($p, 5, 1);
                $e->iBar[6] = array('4', 0, 0, $this->iSymbols[$pi][4]);
                break;
            default:
                JpgraphError::RaiseL(1004, $this->iType);
        }
        $e->iBar[7] = array(0, 0, 1, $this->iGuards[1]);
        return $e;
    }

    function Validate($aData)
    {
        if (strlen($aData) != 11) {
            return false;
        }
        $ns = substr($aData, 0, 1);
        if ($ns != '0' && $ns != '1') {
            return false;
        }
        $mnr = substr($aData, 1, 5);
        $mnr3 = substr($mnr, 2, 3);
        $mnr2 = substr($mnr3, 1, 2);
        $inr = substr($aData, 6, 5);
        if ($mnr3 == '000' || $mnr3 == '100' || $mnr3 == '200') {
            if ($inr > 999) {
                return false;
            }
            $this->iType = 0;
        } elseif (substr($mnr3, 1, 2) == '00' && substr($mnr3, 0, 1) >= '3' && substr($mnr3, 0, 1) <= '9') {
            if ($inr > 99) {
                return false;
            }
            $this->iType = 1;
        } elseif (substr($mnr2, 1, 1) == '0' && substr($mnr2, 0, 1) >= '1' && substr($mnr2, 0, 1) <= '9') {
            if ($inr > 9) {
                return false;
            }
            $this->iType = 2;
        } else {
            if ($inr > 9 || $inr < 5) {
                return false;
            }
            $this->iType = 3;
        }
        return true;
    }

    function GetChecksum($aData)
    {
        $n = $this->CalcChecksum($aData, array(0, 2, 4, 6, 8, 10), 3, array(1, 3, 5, 7, 9), 1, 10);
        return $n;
    }
}

class BarcodeEncode_CODE39 extends BarcodeEncode
{
    var $iSymbolPos = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ-. $/+%';
    var $iSymbol = array(
        '111221211',
        '211211112',
        '112211112',
        '212211111',
        '111221112',
        '211221111',
        '112221111',
        '111211212',
        '211211211',
        '112211211',
        '211112112',
        '112112112',
        '212112111',
        '111122112',
        '211122111',
        '112122111',
        '111112212',
        '211112211',
        '112112211',
        '111122211',
        '211111122',
        '112111122',
        '212111121',
        '111121122',
        '211121121',
        '112121121',
        '111111222',
        '211111221',
        '112111221',
        '111121221',
        '221111112',
        '122111112',
        '222111111',
        '121121112',
        '221121111',
        '122121111',
        '121111212',
        '221111211',
        '122111211',
        '121212111',
        '121211121',
        '121112121',
        '111212121'
    );
    var $iGuard = '121121211';
    var $iExtEscape = '%$$$$$$$$$$$$$$$$$$$$$$$$$$%%%%% //////////// / /%%%%%% %%%%%%++++++++++++++++++++++++++%%%%%';
    var $iExtSymbol = 'UABCDEFGHIJKLMNOPQRSTUVWXYZABCDE ABCDEFGHIJKL-.O0123456789ZFGHIJVABCDEFGHIJKLMNOPQRSTUVWXYZKLMNOWABCDEFGHIJKLMNOPQRSTUVWXYZPQRST';
    var $iUseExtended = false;

    function BarcodeEncode_CODE39()
    {
        parent::BarcodeEncode();
        $this->iName = 'CODE 39';
    }

    function UseExtended($f = true)
    {
        $this->iUseExtended = $f;
    }

    function Enc($aData)
    {
        parent::Enc($aData);
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = '*' . $aData;
        if ($this->iUseChecksum) {
            $e->iData .= $this->iSymbolPos[$this->GetChecksum($aData)];
        }
        $e->iData .= '*';
        $e->iInterCharModuleSpace = true;
        $e->iStrokeDataBelow = true;
        $e->iBar[0] = array(0, 1, 1, $this->iGuard);
        $n = strlen($aData);
        $bpos = 1;
        for ($i = 0; $i < $n; ++$i) {
            $c = substr($aData, $i, 1);
            $co = ord($c);
            if ($this->iUseExtended) {
                $esc = substr($this->iExtEscape, $co, 1);
                $sym = substr($this->iExtSymbol, $co, 1);
                if ($esc != ' ') {
                    $p = strpos($this->iSymbolPos, $esc);
                    $e->iBar[$bpos] = array($esc, 1, 1, $this->iSymbol[$p]);
                    $bpos++;
                }
                $p = strpos($this->iSymbolPos, $sym);
                $e->iBar[$bpos] = array($sym, 1, 1, $this->iSymbol[$p]);
            } else {
                $p = strpos($this->iSymbolPos, $c);
                $e->iBar[$bpos] = array($c, 1, 1, $this->iSymbol[$p]);
            }
            $bpos++;
        }
        if ($this->iUseChecksum) {
            $cs = $this->GetChecksum($aData);
            $e->iBar[$bpos] = array(0, 1, 1, $this->iSymbol[$cs]);
            $bpos++;
        }
        $e->iBar[$bpos] = array(0, 1, 1, $this->iGuard);
        return $e;
    }

    function Validate($aData)
    {
        $n = strlen($aData);
        if ($this->iUseExtended) {
            for ($i = 0; $i < $n; ++$i) {
                if (substr($aData, $i, 1) > 127) {
                    return false;
                }
            }
        } else {
            for ($i = 0; $i < $n; ++$i) {
                if (strpos($this->iSymbolPos, substr($aData, $i, 1)) === false) {
                    return false;
                }
            }
        }
        return true;
    }

    function GetChecksum($aData)
    {
        $n = strlen($aData);
        $sum = 0;
        for ($i = 0; $i <= $n; ++$i) {
            $sum += ord(substr($aData, $i, 1));
        }
        $checksum = $sum % 43;
        return $checksum;
    }
}

class BarcodeEncode_BOOKLAND extends BarcodeEncode_EAN13
{
    var $iBooklandNumberSystem = '978';

    function BarcodeEncode_BOOKLAND()
    {
        parent::BarcodeEncode();
        $this->iName = 'BOOKLAND (ISBN)';
    }

    function Enc($aData)
    {
        if (substr($aData, 0, 3) != '978') {
            $aData = $this->iBooklandNumberSystem . $aData;
        }
        return parent::Enc($aData);
    }
}

DEFINE('CODE128_A', 0);
DEFINE('CODE128_B', 1);
DEFINE('CODE128_C', 2);
DEFINE('EA_FUNC1', 128);
DEFINE('EA_FUNC2', 129);
DEFINE('EA_FUNC3', 130);
DEFINE('EA_FUNC4', 131);
DEFINE('STARTA', 103);
DEFINE('STARTB', 104);
DEFINE('STARTC', 105);
DEFINE('SHIFT', 98);
DEFINE('CODEA', 101);
DEFINE('CODEB', 100);
DEFINE('CODEC', 99);
DEFINE('ENDGUARD', 106);
DEFINE('FUNC1', 102);
DEFINE('FUNC2', 97);
DEFINE('FUNC3', 96);

class BarcodeEncode_CODE128 extends BarcodeEncode
{
    var $iDefaultStart = CODE128_B;
    var $iStart = array(STARTA, STARTB, STARTC);
    var $iCharsets = array(CODEA, CODEB, CODEC);
    var $iCurrCharset = -1;
    var $iSymbols = array(
        "212222",
        "222122",
        "222221",
        "121223",
        "121322",
        "131222",
        "122213",
        "122312",
        "132212",
        "221213",
        "221312",
        "231212",
        "112232",
        "122132",
        "122231",
        "113222",
        "123122",
        "123221",
        "223211",
        "221132",
        "221231",
        "213212",
        "223112",
        "312131",
        "311222",
        "321122",
        "321221",
        "312212",
        "322112",
        "322211",
        "212123",
        "212321",
        "232121",
        "111323",
        "131123",
        "131321",
        "112313",
        "132113",
        "132311",
        "211313",
        "231113",
        "231311",
        "112133",
        "112331",
        "132131",
        "113123",
        "113321",
        "133121",
        "313121",
        "211331",
        "231131",
        "213113",
        "213311",
        "213131",
        "311123",
        "311321",
        "331121",
        "312113",
        "312311",
        "332111",
        "314111",
        "221411",
        "431111",
        "111224",
        "111422",
        "121124",
        "121421",
        "141122",
        "141221",
        "112214",
        "112412",
        "122114",
        "122411",
        "142112",
        "142211",
        "241211",
        "221114",
        "413111",
        "241112",
        "134111",
        "111242",
        "121142",
        "121241",
        "114212",
        "124112",
        "124211",
        "411212",
        "421112",
        "421211",
        "212141",
        "214121",
        "412121",
        "111143",
        "111341",
        "131141",
        "114113",
        "114311",
        "411113",
        "411311",
        "113141",
        "114131",
        "311141",
        "411131",
        "211412",
        "211214",
        "211232",
        "2331112"
    );

    function BarcodeEncode_CODE128()
    {
        parent::BarcodeEncode();
        $this->iName = 'CODE 128';
    }

    function Validate($aData)
    {
        $n = strlen($aData);
        $i = 0;
        while ($i < $n) {
            $c = ord(substr($aData, $i, 1));
            if ($c >= 132) {
                return false;
            }
            ++$i;
        }
        return true;
    }

    function EncodeSymbolC($aSym1, $aSym2)
    {
        $val = 10 * $aSym1 + $aSym2;
        if ($val > 99) {
            JpGraphError::RaiseL(1005, $aSym1, $aSym2);
        } else {
            return $val;
        }
    }

    function EncodeSymbolAB($aSym)
    {
        switch ($aSym) {
            case EA_FUNC1:
                return FUNC1;
            case EA_FUNC2:
                return FUNC2;
            case EA_FUNC3:
                return FUNC3;
            case EA_FUNC4:
                if ($this->iCurrCharset == CODE128_A) {
                    return CODEA;
                } else {
                    return CODEB;
                }
        }
        if ($aSym >= 32 && $aSym <= 95) {
            return $aSym - 32;
        }
        if ($aSym < 32) {
            if ($this->iCurrCharset != CODE128_A) {
                JpGraphError::RaiseL(1006);
            }
            return $aSym + 64;
        }
        if ($aSym == 127) {
            if ($this->iCurrCharset != CODE128_B) {
                JpGraphError::RaiseL(1007);
            }
            return 95;
        }
        if ($aSym >= 96 && $aSym <= 126) {
            if ($this->iCurrCharset != CODE128_B) {
                JpGraphError::RaiseL(1008);
            }
            return $aSym - 32;
        }
        JpGraphError::RaiseL(1003, $aSym);
    }

    function CharSetRequired($aString)
    {
        $n = strlen($aString);
        for ($i = 0; $i < $n; ++$i) {
            $c = ord(substr($aString, $i, 1));
            if ($c < 32) {
                return CODE128_A;
            }
            if ($c >= 96 && $c <= 127) {
                return CODE128_B;
            }
        }
        return -1;
    }

    function SwitchToC($aData, &$aPos, &$aSyms)
    {
        $maxlen = strlen($aData);
        for ($i = 0; $i + $aPos <= $maxlen && ctype_digit(substr($aData, $i + $aPos, 1)); ++$i) {
            ;
        }
        if ($i >= 4) {
            if ($i & 1) {
                $aSyms[] = $this->EncodeSymbolAB(ord(substr($aData, $aPos, 1)));
                $aPos++;
            }
            $aSyms[] = $this->iCharsets[CODE128_C];
            return true;
        }
        return false;
    }

    function Enc($aData)
    {
        if ($this->iUseTilde) {
            $aData = str_replace('~1', chr(EA_FUNC1), $aData);
            $aData = str_replace('~2', chr(EA_FUNC2), $aData);
            $aData = str_replace('~3', chr(EA_FUNC3), $aData);
        }
        parent::Enc($aData);
        $n = strlen($aData);
        $syms = array();
        if ($aData[0] == chr(EA_FUNC1)) {
            $this->iCurrCharset = CODE128_C;
            $syms[] = STARTC;
            $i = 1;
            $syms[] = FUNC1;
        } else {
            if ($n == 2 && ctype_digit(substr($aData, 0, 2))) {
                $this->iCurrCharset = CODE128_C;
            } elseif ($n >= 4 && ctype_digit(substr($aData, 0, 4))) {
                $this->iCurrCharset = CODE128_C;
            } else {
                $this->iCurrCharset = $this->CharSetRequired($aData);
                if ($this->iCurrCharset == -1) {
                    $this->iCurrCharset = $this->iDefaultStart;
                }
            }
            $syms[] = $this->iStart[$this->iCurrCharset];
            $i = 0;
        }
        while ($i < $n) {
            switch ($this->iCurrCharset) {
                case CODE128_A:
                    if ($this->SwitchToC($aData, $i, $syms)) {
                        $this->iCurrCharset = CODE128_C;
                    } else {
                        if ($this->CharSetRequired(substr($aData, $i, 1)) == CODE128_B) {
                            if ($this->CharSetRequired(substr($aData, $i + 1)) == CODE128_B) {
                                $this->iCurrCharset = CODE128_B;
                                $syms[] = $this->iCharsets[$this->iCurrCharset];
                            } else {
                                $syms[] = SHIFT;
                                $syms[] = $this->EncodeSymbolAB(ord(substr($aData, $i, 1)));
                                ++$i;
                            }
                        } else {
                            $syms[] = $this->EncodeSymbolAB(ord(substr($aData, $i, 1)));
                            ++$i;
                        }
                    }
                    break;
                case CODE128_B:
                    if ($this->SwitchToC($aData, $i, $syms)) {
                        $this->iCurrCharset = CODE128_C;
                    } elseif ($this->CharSetRequired(substr($aData, $i, 1)) == CODE128_A) {
                        if ($this->CharSetRequired(substr($aData, $i + 1)) == CODE128_A) {
                            $this->iCurrCharset = CODE128_A;
                            $syms[] = $this->iCharsets[$this->iCurrCharset];
                        } else {
                            $syms[] = SHIFT;
                            $syms[] = $this->EncodeSymbolAB(ord(substr($aData, $i, 1)));
                            ++$i;
                        }
                    } else {
                        $syms[] = $this->EncodeSymbolAB(ord(substr($aData, $i, 1)));
                        ++$i;
                    }
                    break;
                case CODE128_C:
                    if (substr($aData, $i, 1) == chr(EA_FUNC1)) {
                        $syms[] = FUNC1;
                        $i++;
                    } elseif (substr($aData, $i, 1) == chr(EA_FUNC2)) {
                        $syms[] = FUNC2;
                        $i++;
                    } elseif (substr($aData, $i, 1) == chr(EA_FUNC3)) {
                        $syms[] = FUNC3;
                        $i++;
                    } elseif ($i < $n - 1 && ctype_digit(substr($aData, $i, 2))) {
                        $syms[] = (substr($aData, $i, 1) - '0') * 10 + (substr($aData, $i + 1, 1) - '0');
                        $i += 2;
                    } else {
                        $tmp = $this->CharSetRequired(substr($aData, $i));
                        $this->iCurrCharset = $tmp == -1 ? $this->iDefaultStart : $tmp;
                        $syms[] = $this->iCharsets[$this->iCurrCharset];
                    }
                    break;
            }
        }
        $syms[] = ENDGUARD;
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = str_replace(chr(EA_FUNC1), '', $aData);
        $e->iData = str_replace(chr(EA_FUNC2), '', $e->iData);
        $e->iData = str_replace(chr(EA_FUNC3), '', $e->iData);
        $e->iStrokeDataBelow = true;
        $n = count($syms);
        $checksum = $syms[0];
        for ($i = 0; $i < $n - 1; ++$i) {
            $e->iBar[$i] = array($syms[$i], 1, 1, $this->iSymbols[$syms[$i]]);
            $checksum += $syms[$i] * $i;
        }
        $checksum %= 103;
        $e->iBar[$n - 1] = array($checksum, 1, 1, $this->iSymbols[$checksum]);
        $e->iBar[$n] = array($syms[$n - 1], 1, 1, $this->iSymbols[$syms[$n - 1]]);
        return $e;
    }
}

class BarcodeEncode_EAN128 extends BarcodeEncode_CODE128
{
    function BarcodeEncode_EAN128()
    {
        parent::BarcodeEncode();
        $this->iName = 'EAN-128';
        $this->iUseTilde = true;
    }

    function Enc($aData)
    {
        if (ord(substr($aData, 0, 1)) != EA_FUNC1) {
            $aData = chr(EA_FUNC1) . $aData;
        }
        return parent::Enc($aData);
    }

    function GetDataLength($aData)
    {
        $c1 = substr($aData, 0, 1);
        $c2 = substr($aData, 1, 1);
        $c3 = substr($aData, 2, 1);
        if ($c1 == '0') {
            if ($c2 == '0') {
                return 18 + 2;
            }
            if ($c2 == '1' || $c2 == '2') {
                return 14 + 2;
            }
        }
        if ($c1 == '1') {
            if ($c2 == '0') {
                return -(20 + 2);
            }
            if ($c2 == '1' || $c2 == '3' || $c2 == '5' || $c2 == '7') {
                return 6 + 2;
            }
        }
        if ($c1 == '2') {
            if ($c2 == '0') {
                return 2 + 2;
            }
            if ($c2 == '1') {
                return -(20 + 2);
            }
            if ($c2 == '2') {
                return -(29 + 2);
            }
            if ($c2 == '3') {
                return -(29 + 3);
            }
            if ($c3 == '0' && ($c2 == '4' || $c2 == '5')) {
                return -(30 + 3);
            }
        }
        if ($c1 == '3' && ($c2 >= '1' && $c2 <= '6') && ($c3 >= '0' && $c3 <= '9')) {
            return 6 + 4;
        }
        if ($c1 == '3' && $c2 == '7') {
            return 8 + 2;
        }
        if ($c1 == '4') {
            if ($c2 == '0' && $c3 == '0') {
                return 29 + 3;
            }
            if ($c2 == '1' && ($c3 >= '0' || $c3 <= '2')) {
                return 13 + 3;
            }
            if ($c2 == '2' && $c3 == '0') {
                return -(9 + 3);
            }
            if ($c2 == '2' && $c3 == '1') {
                return -(12 + 3);
            }
        }
        if ($c1 == '8' && $c2 == '0' && $c3 == '0') {
            $c4 = substr($aData, 3, 1);
            if ($c4 == '1') {
                return 14 + 4;
            }
            if ($c4 == '2') {
                return -(20 + 4);
            }
            if ($c4 == '3' || $c4 == '4') {
                return -(30 + 4);
            }
            if ($c4 == '5') {
                return 6 + 4;
            }
            if ($c2 == '1' || $c2 == '0') {
                if ($c4 == '0') {
                    return 6 + 4;
                }
                if ($c4 == '1') {
                    return 10 + 4;
                }
                if ($c4 == '2') {
                    return 2 + 4;
                }
            }
        }
        if ($c1 == '9' && ($c2 >= '0' && $c2 <= '9')) {
            return -(30 + 2);
        }
        return false;
    }

    function ValidateChunk($aChunk)
    {
        $maxlen = $this->GetDataLength($aChunk);
        if ($maxlen > 0) {
            if (ctype_digit($aChunk) == false) {
                return false;
            }
        }
        if (strlen($aChunk) > abs($maxlen) + 1) {
            return false;
        }
        return true;
    }

    function Validate($aData)
    {
        if (ord(substr($aData, 0, 1)) != EA_FUNC1) {
            $aData = chr(EA_FUNC1) . $aData;
        }
        $n = strlen($aData);
        $i = 0;
        while ($i < $n) {
            if ($aData[$i] == chr(EA_FUNC1)) {
                $end = strpos($aData, chr(EA_FUNC1), $i + 1);
                if ($end === false) {
                    return $this->ValidateChunk(substr($aData, $i + 1));
                } else {
                    if (!$this->ValidateChunk(substr($aData, $i + 1, $end - 1 - $i))) {
                        return false;
                    }
                }
                $i = $end;
            }
        }
        return true;
    }
}

class BarcodeEncode_CODE25 extends BarcodeEncode
{
    var $iSymbols = array('11331', '31113', '13113', '33111', '11313', '31311', '13311', '11133', '31131', '13131');
    var $iGuard = array(array('212111', '21112'), array('1111', '311'));

    function BarcodeEncode_CODE25()
    {
        parent::BarcodeEncode();
        $this->iName = 'INDUSTRIAL 2 OF 5';
    }

    function GetChecksum($aData)
    {
        $n = strlen($aData);
        $sum = 0;
        for ($i = 0; $i < $n; ++$i) {
            $c = substr($aData, $n - 1 - $i, 1);
            if (($i & 1) == 0) {
                $sum += ($c + 0) * 3;
            } else {
                $sum += ($c + 0);
            }
        }
        $d = ($sum % 10);
        if ($d > 0) {
            return 10 - $d;
        } else {
            return 0;
        }
    }

    function Enc($aData)
    {
        return $this->_Enc($aData, false);
    }

    function EncI($aData)
    {
        return $this->_Enc($aData, true);
    }

    function _Enc($aData, $aInterleave = false)
    {
        parent::Enc($aData);
        if ($this->iUseChecksum) {
            $aData .= $this->GetChecksum($aData);
        }
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = $aData;
        $e->iInterCharModuleSpace = false;
        $e->iStrokeDataBelow = true;
        if ($this->iUseChecksum) {
            $e->iInfo = "Checkdigit=" . substr($aData, strlen($aData) - 1, 1);
        }
        $guardtype = $aInterleave ? 1 : 0;
        $e->iBar[0] = array(0, 1, 1, $this->iGuard[$guardtype][0]);
        $n = strlen($aData);
        $bpos = 1;
        $i = 0;
        while ($i < $n) {
            $c = substr($aData, $i, 1);
            $s = $this->iSymbols[$c + 0];
            $ms = '';
            if ($aInterleave) {
                ++$i;
                $c2 = substr($aData, $i, 1);
                $s2 = $this->iSymbols[$c2 + 0];
                for ($j = 0; $j < 5; ++$j) {
                    $ms .= substr($s, $j, 1) . substr($s2, $j, 1);
                }
            } else {
                for ($j = 0; $j < 5; ++$j) {
                    $ms .= substr($s, $j, 1) . '1';
                }
            }
            $e->iBar[$bpos] = array($c, 1, 1, $ms);
            $bpos++;
            ++$i;
        }
        $e->iBar[$bpos] = array(0, 1, 1, $this->iGuard[$guardtype][1]);
        return $e;
    }

    function Validate($aData)
    {
        return ctype_digit($aData);
    }
}

class BarcodeEncode_CODEI25 extends BarcodeEncode_CODE25
{
    function BarcodeEncode_CODEI25()
    {
        parent::BarcodeEncode();
        $this->iName = 'INTERLEAVED 2 OF 5';
    }

    function Enc($aData)
    {
        return parent::EncI($aData);
    }

    function Validate($aData)
    {
        return (((strlen($aData) & 1) == 0 && !$this->iUseChecksum) || ((strlen($aData) & 1) == 1 && $this->iUseChecksum)) && ctype_digit($aData);
    }
}

class BarcodeEncode_CODABAR extends BarcodeEncode
{
    var $iSymbols = array(
        '1111122',
        '1111221',
        '1112112',
        '2211111',
        '1121121',
        '2111121',
        '1211112',
        '1211211',
        '1221111',
        '2112111',
        '1112211',
        '1122111',
        '2111212',
        '2121112',
        '2121211',
        '1122222',
        '1122121',
        '1212112',
        '1112122',
        '1112221',
    );
    var $iStartStop = array('A', 'B', 'C', 'D');
    var $iSymbolPos = '0123456789-$:/.+ABCD';

    function BarcodeEncode_CODABAR()
    {
        parent::BarcodeEncode();
        $this->iName = 'CODABAR';
    }

    function Enc($aData)
    {
        parent::Enc($aData);
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iInterCharModuleSpace = true;
        $e->iStrokeDataBelow = true;
        $c = substr($aData, 0, 1);
        if (in_array($c, $this->iStartStop) == false) {
            $aData = 'A' . $aData . 'B';
        }
        $e->iData = $aData;
        $n = strlen($aData);
        $bpos = 0;
        $i = 0;
        while ($i < $n) {
            $c = substr($aData, $i, 1);
            $p = strpos($this->iSymbolPos, $c);
            $e->iBar[$bpos] = array($c, 1, 1, $this->iSymbols[$p]);
            $bpos++;
            ++$i;
        }
        return $e;
    }

    function Validate($aData)
    {
        $n = strlen($aData);
        $sf = 0;
        for ($i = 0; $i < $n; ++$i) {
            $c = substr($aData, $i, 1);
            if (strpos($this->iSymbolPos, $c) === false) {
                return false;
            }
            if (in_array($c, $this->iStartStop)) {
                $sf++;
                if ($i > 0 && $i < $n - 1) {
                    return false;
                }
            }
        }
        if ($sf > 0 && $sf != 2) {
            return false;
        }
        return true;
    }
}

class BarcodeEncode_CODE11 extends BarcodeEncode
{
    var $iSymbols = array(
        '11112',
        '21112',
        '12112',
        '22111',
        '11212',
        '21211',
        '12211',
        '11122',
        '21121',
        '21111',
        '11211'
    );
    var $iSymbolPos = '0123456789-';
    var $iGuard = '11221';

    function BarcodeEncode_CODE11()
    {
        parent::BarcodeEncode();
        $this->iName = 'CODE 11';
    }

    function GetChecksum($aData)
    {
        $sum = 0;
        $n = strlen($aData);
        $weight = 1;
        for ($i = 0; $i < $n; ++$i) {
            $c = substr($aData, $n - 1 - $i, 1);
            if ($c == '-') {
                $c = 10;
            }
            $sum += $weight * $c;
            $weight++;
            if ($weight > 10) {
                $weight = 1;
            }
        }
        $chk = substr($this->iSymbolPos, ($sum % 11), 1);
        $aData .= $chk;
        $sum = 0;
        ++$n;
        $weight = 1;
        for ($i = 0; $i < $n; ++$i) {
            $c = substr($aData, $n - 1 - $i, 1);
            if ($c == '-') {
                $c = 10;
            }
            $sum += $weight * $c;
            $weight++;
            if ($weight > 9) {
                $weight = 1;
            }
        }
        $chk .= substr($this->iSymbolPos, ($sum % 11), 1);
        return $chk;
    }

    function Enc($aData)
    {
        parent::Enc($aData);
        if ($this->iUseChecksum) {
            $aData .= $this->GetChecksum($aData);
        }
        $e = new BarcodePrintSpec();
        $e->iEncoding = $this->GetName();
        $e->iData = $aData;
        $e->iInterCharModuleSpace = true;
        $e->iStrokeDataBelow = true;
        $e->iBar[0] = array(0, 1, 1, $this->iGuard);
        $n = strlen($aData);
        $bpos = 1;
        $i = 0;
        while ($i < $n) {
            $c = substr($aData, $i, 1);
            $p = strpos($this->iSymbolPos, $c);
            $e->iBar[$bpos] = array($c, 1, 1, $this->iSymbols[$p]);
            $bpos++;
            ++$i;
        }
        $e->iBar[$bpos] = array(0, 1, 1, $this->iGuard);
        return $e;
    }

    function Validate($aData)
    {
        $n = strlen($aData);
        for ($i = 0; $i < $n; ++$i) {
            if (strpos($this->iSymbolPos, substr($aData, $i, 1)) === false) {
                return false;
            }
        }
        return true;
    }
}

class BarcodeEncode_CODE93 extends BarcodeEncode
{
    function BarcodeEncode_CODE93()
    {
        JpGraphError::RaiseL(1009);
        parent::BarcodeEncode();
        $this->iName = 'CODE-93';
    }

    function Enc($aData)
    {
        parent::Enc($aData);
    }

    function Validate($aData)
    {
        return false;
    }
}

class BarcodeEncode_POSTNET extends BarcodeEncode
{
    function BarcodeEncode_POSTNET()
    {
        JpGraphError::RaiseL(1010);
        parent::BarcodeEncode();
        $this->iName = 'POSTNET';
    }

    function Enc($aData)
    {
        parent::Enc($aData);
    }

    function Validate($aData)
    {
        return false;
    }
}

class BarcodePrintSpec
{
    var $iLongBarFraction = 0.95;
    var $iModuleWidth = 1;
    var $iEncoding;
    var $iData;
    var $iLeftData;
    var $iRightData;
    var $iLeftMargin = 15;
    var $iRightMargin = 15;
    var $iBar;
    var $iFontSizeLarge = 12, $iFontSizeSmall = 10;
    var $iInterCharModuleSpace = false;
    var $iStrokeDataAbove = false, $iStrokeDataBelow = false;
    var $iInfo;

    function BarcodePrintSpec()
    {
        $this->iBar = array();
    }
}

class BackendFactory
{
    function Create($aBackend, $aEncoder, $aReport = false)
    {
        $backends = array('IMAGE', 'PS');
        if (array_search($aBackend, $backends) === false) {
            if ($aReport) {
                JpGraphError::RaiseL(1011, $aBackend);
            } else {
                return false;
            }
        }
        $b = 'OutputBackend_' . $aBackend;
        return new $b($aEncoder);
    }
}

class OutputBackend
{
    var $iModuleWidth = 1;
    var $iUseChecksum = 0;
    var $iNoHumanText = false;
    var $iDataBelowMargin = 6;
    var $iFontFam = -1, $iFontStyle = FS_NORMAL, $iFontSize = 10;
    var $iSmallFontFam = FF_FONT1, $iSmallFontStyle = FS_NORMAL, $iSmallFontSize = 8;
    var $iColor = 'black', $iBkgColor = 'white';
    var $iVertical = false;
    var $iShowFrame = false;
    var $iDebugBackground = false;
    var $iHeight = 70;
    var $iScale = 1;
    var $iEncoder = null;
    var $iAdjLeftMargin = 15, $iAdjRightMargin = 15;
    var $iBottomMargin = 10;
    var $iTopMargin = 3;
    var $iFrameColor = 'black';
    var $iHumanTxt = '';

    function AdjustSpec(&$aSpec)
    {
        $aSpec->iModuleWidth = $this->iModuleWidth;
        if ($this->iNoHumanText) {
            $aSpec->iStrokeDataBelow = false;
            $aSpec->iLeftData = '';
            $aSpec->iRightData = '';
        }
        $aSpec->iLeftMargin = $this->iAdjLeftMargin;
        $aSpec->iRightMargin = $this->iAdjRightMargin;
    }

    function SetMargins($aLeft, $aRight, $aTop = 3, $aBottom = 10)
    {
        $this->iAdjLeftMargin = $aLeft;
        $this->iAdjRightMargin = $aRight;
        $this->iTopMargin = $aTop;
        $this->iBottomMargin = $aBottom;
    }

    function SetVertical($aFlg = true)
    {
        $this->iVertical = $aFlg;
    }

    function SetHumanText($aTxt)
    {
        $this->iHumanTxt = $aTxt;
    }

    function SetScale($aScale)
    {
        $this->iScale = $aScale;
    }

    function SetModuleWidth($aWidth)
    {
        $this->iModuleWidth = $aWidth;
    }

    function SetHeight($aHeight)
    {
        $this->iHeight = $aHeight;
    }

    function HideText($aFlg = true)
    {
        $this->iNoHumanText = $aFlg;
    }

    function NoText($aFlg = true)
    {
        $this->iNoHumanText = $aFlg;
    }

    function ShowFrame($aFlg = true)
    {
        $this->iShowFrame = $aFlg;
    }

    function SetFrameColor($aColor)
    {
        $this->iFrameColor = $aColor;
    }

    function AddChecksum($aFlag = true)
    {
        $this->iUseChecksum = $aFlag;
    }

    function SetFont($aFontFam, $aFontStyle, $aFontSize)
    {
        $this->iFontFam = $aFontFam;
        $this->iFontStyle = $aFontStyle;
        $this->iFontSize = $aFontSize;
    }

    function SetSmallFont($aFontFam, $aFontStyle, $aFontSize)
    {
        $this->iSmallFontFam = $aFontFam;
        $this->iSmallFontStyle = $aFontStyle;
        $this->iSmallFontSize = $aFontSize;
    }

    function SetColor($aColor, $aBkgColor)
    {
        $this->iColor = $aColor;
        $this->iBkgColor = $aBkgColor;
    }
}

class OutputBackend_IMAGE extends OutputBackend
{
    var $iImgFormat = 'png';

    function OutputBackend_IMAGE($aEncoder)
    {
        $this->iEncoder = $aEncoder;
    }

    function SetImgFormat($aFormat)
    {
        $this->iImgFormat = $aFormat;
    }

    function SetModuleWidth($aWidth)
    {
        $this->iModuleWidth = round($aWidth);
    }

    function Rotate($src_img, $degrees = 90)
    {
        if (!function_exists('imagerotate')) {
            return $src_img;
        }
        $degrees %= 360;
        if ($degrees == 0) {
            $dst_img = $src_img;
        } elseif ($degrees == 180) {
            $dst_img = imagerotate($src_img, $degrees, 0);
        } else {
            $width = imagesx($src_img);
            $height = imagesy($src_img);
            if ($width > $height) {
                $size = $width;
            } else {
                $size = $height;
            }
            $dst_img = imagecreatetruecolor($size, $size);
            imagecopy($dst_img, $src_img, 0, 0, 0, 0, $width, $height);
            $dst_img = imagerotate($dst_img, $degrees, 0);
            $src_img = $dst_img;
            $dst_img = imagecreatetruecolor($height, $width);
            if ((($degrees == 90) && ($width > $height)) || (($degrees == 270) && ($width < $height))) {
                imagecopy($dst_img, $src_img, 0, 0, 0, 0, $size, $size);
            }
            if ((($degrees == 270) && ($width > $height)) || (($degrees == 90) && ($width < $height))) {
                imagecopy($dst_img, $src_img, 0, 0, $size - $height, $size - $width, $size, $size);
            }
        }
        return $dst_img;
    }

    function Stroke($aData, $aFile = '', $aShowDetails = false, $aShowEncodingDetails = false)
    {
        $textmargin = 5;
        $this->iEncoder->AddChecksum($this->iUseChecksum);
        $spec = $this->iEncoder->Enc($aData);
        $this->AdjustSpec($spec);
        if ($this->iFontFam == -1) {
            if ($this->iModuleWidth > 1) {
                $this->iFontFam = FF_FONT2;
                $this->iFontStyle = FS_BOLD;
            } else {
                $this->iFontFam = FF_FONT1;
                $this->iFontStyle = FS_BOLD;
            }
        }
        $s = '';
        $n = count($spec->iBar);
        $g = new CanvasGraph(0, 0);
        $g->img->SetImgFormat($this->iImgFormat);
        if ($aShowDetails) {
            $s = $spec->iEncoding . "\n";
            $s .= 'Data: ' . $spec->iData . "\n";
            if ($spec->iInfo != '') {
                $s .= 'Info: ' . $spec->iInfo . "\n";
            }
        }
        $w = round($spec->iModuleWidth);
        $totwidth = $spec->iLeftMargin * $w;
        $n = count($spec->iBar);
        for ($i = 0; $i < $n; ++$i) {
            $b = $spec->iBar[$i];
            $bn = strlen($b[3]);
            for ($j = 0; $j < $bn; ++$j) {
                $wb = substr($b[3], $j, 1) * $w;
                $totwidth += $wb;
            }
        }
        if ($spec->iInterCharModuleSpace) {
            $totwidth += ($n - 2) * $w;
        }
        $totwidth += $spec->iRightMargin * $w + 1;
        $height = $this->iHeight;
        if ($aShowDetails) {
            $g->img->SetFont(FF_FONT2);
            $height += $g->img->GetTextHeight($s);
        }
        $g->img->SetFont($this->iFontFam, $this->iFontStyle, $this->iFontSize);
        $th = $g->img->GetTextHeight($spec->iData);
        if ($spec->iStrokeDataBelow) {
            $height += $th + $this->iDataBelowMargin;
        }
        if ($height < round(0.15 * ($totwidth - $spec->iRightMargin * $w - $spec->iLeftMargin * $w))) {
            $height = round(0.15 * $totwidth);
        }
        $g->img->SetFont(FF_FONT2);
        $tw = 2 * $textmargin + $g->img->GetTextWidth($s);
        $width = $totwidth;
        if ($width < $tw) {
            $width = $tw;
        }
        if ($aShowEncodingDetails) {
            $g->img->SetFont(FF_FONT2);
            $height += $n * $g->img->GetTextHeight('0');
            $width = max(300, $totwidth);
        }
        $g = new CanvasGraph($width, $height);
        $g->img->SetImgFormat($this->iImgFormat);
        $g->SetMarginColor('white');
        if ($this->iShowFrame) {
            $g->frame_color = $this->iFrameColor;
            $g->InitFrame();
        }
        $g->img->SetColor('black');
        $x = $w * $spec->iLeftMargin;
        $ystart = $this->iTopMargin;
        $yend = $height - $this->iBottomMargin - 1;
        if ($aShowDetails) {
            $ystart += $g->img->GetTextHeight($s);
        }
        if ($aShowEncodingDetails) {
            $g->img->SetFont(FF_FONT2);
            $ystart += $n * $g->img->GetTextHeight('0');
        }
        if ($spec->iStrokeDataBelow) {
            $yend -= ($th + $this->iDataBelowMargin);
        }
        $inunder = false;
        $under_s = '';
        for ($i = 0; $i < $n; ++$i) {
            $b = $spec->iBar[$i];
            if ($aShowEncodingDetails) {
                $s .= sprintf("%02d", $i) . " : $b[0], $b[1], $b[2], $b[3]\n";
            }
            $bn = strlen($b[3]);
            if ($b[2] == 0 && !$this->iNoHumanText) {
                if (!$inunder) {
                    $inunder = true;
                    $under_x = $x;
                    $under_s = $b[0];
                } else {
                    $under_s .= $b[0];
                }
            } else {
                if ($inunder) {
                    $inunder = false;
                    if ($under_s != '') {
                        $t = new Text($under_s, ($under_x + $x - 1) / 2, $yend - $th / 1.3);
                        $t->SetFont($this->iFontFam, $this->iFontStyle, $this->iFontSize);
                        $t->Align('center', 'top');
                        $t->Stroke($g->img);
                    }
                }
            }
            $startx = $x;
            for ($j = 0; $j < $bn; ++$j) {
                $wb = substr($b[3], $j, 1) * $w;
                if ($j % 2 == $b[1]) {
                    $g->img->SetColor($this->iBkgColor);
                } else {
                    $g->img->SetColor($this->iColor);
                }
                if ($b[2] == 1 || $this->iNoHumanText) {
                    $g->img->FilledRectangle($x, $ystart, $x + $wb - 1, $yend);
                } else {
                    $g->img->FilledRectangle($x, $ystart, $x + $wb - 1, $yend - $th);
                }
                $x += $wb;
            }
            if ($this->iDebugBackground) {
                $g->SetAlphaBlending();
                if (($i & 1) == 0) {
                    $g->img->SetColor('lightblue@0.5');
                } else {
                    $g->img->SetColor('yellow@0.5');
                }
                $g->img->FilledRectangle($startx, $ystart - 2, $x, $yend);
            }
            if ($spec->iInterCharModuleSpace) {
                $x += $w;
            }
        }
        $g->img->SetColor($this->iColor);
        if (!($spec->iLeftData === '')) {
            $g->img->SetTextAlign('right', 'top');
            $g->img->SetFont($this->iSmallFontFam, $this->iSmallFontStyle, $this->iSmallFontSize);
            $g->img->StrokeText(($w * $spec->iLeftMargin) - 3, $yend - $th, $spec->iLeftData);
        }
        if (!($spec->iRightData === '')) {
            $g->img->SetTextAlign('left', 'top');
            $g->img->SetFont($this->iSmallFontFam, $this->iSmallFontStyle, $this->iSmallFontSize);
            $g->img->StrokeText($x + 3, $yend - $th, $spec->iRightData);
        }
        if ($spec->iStrokeDataBelow) {
            $y = $yend + $this->iDataBelowMargin;
            $bw = $totwidth - $spec->iLeftMargin * $w - $spec->iRightMargin * $w;
            $x = $spec->iLeftMargin * $w + floor($bw / 2);
            $g->img->SetTextAlign('center', 'top');
            $g->img->SetFont($this->iFontFam, $this->iFontStyle, $this->iFontSize);
            if ($this->iHumanTxt !== '') {
                $g->img->StrokeText($x, $y, $this->iHumanTxt);
            } else {
                $g->img->StrokeText($x, $y, $spec->iData);
            }
        }
        if ($aShowDetails) {
            $g->img->SetColor('navy');
            $g->img->SetTextAlign('left', 'top');
            $g->img->SetFont(FF_FONT2);
            $g->img->StrokeText($textmargin, $this->iTopMargin, $s);
        }
        if (ADD_DEMOTXT === true) {
            $t = new Text("<<DEMO>>", $totwidth / 2, $ystart);
            if ($this->iModuleWidth > 1) {
                if ($this->iModuleWidth > 4) {
                    $t->SetFont(FF_ARIAL, FS_BOLD, 32);
                    $step = 140;
                    $yadj = 50;
                } else {
                    $t->SetFont(FF_ARIAL, FS_BOLD, 24);
                    $step = 110;
                    $yadj = 40;
                }
            } else {
                $t->SetFont(FF_ARIAL, FS_BOLD, 18);
                $step = 80;
                $yadj = 30;
            }
            $t->SetColor('red@0.4');
            $t->Align('center', 'center');
            $t->SetAngle(-25);
            $n = ceil($totwidth / $step);
            for ($i = 0; $i < $n; ++$i) {
                $t->SetPos(-30 + $i * $step, ($yend - $ystart) / 2 - $yadj);
                $t->Stroke($g->img);
            }
        }
        if ($this->iVertical) {
            $g->img->img = $this->Rotate($g->img->img, 90);
        }
        if ($this->iScale != 1) {
            $nwidth = round($width * $this->iScale);
            $nheight = round($height * $this->iScale);
            if ($this->iVertical) {
                $tmp = $height;
                $height = $width;
                $width = $tmp;
                $tmp = $nheight;
                $nheight = $nwidth;
                $nwidth = $tmp;
            }
            $img = @imagecreatetruecolor($nwidth, $nheight);
            if ($img) {
                imagealphablending($img, true);
                imagecopyresampled($img, $g->img->img, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
                $g->img->CreateImgCanvas($nwidth, $nheight);
                $g->img->img = $img;
            } else {
                return false;
            }
        }
        return $g->Stroke($aFile);
    }
}

class OutputBackend_PS extends OutputBackend
{
    var $iEPS = false;
    var $ixoffset = 0, $iyoffset = 10;

    function OutputBackend_PS($aEncoder)
    {
        $this->iEncoder = $aEncoder;
        $this->iFontSize = 12;
        $this->iSmallFontSize = 10;
        $this->iModuleWidth = 1.1;
        $this->iBottomMargin = 6;
    }

    function SetEPS($aFlg = true)
    {
        $this->iEPS = $aFlg;
    }

    function Stroke($aData, $aFile = '', $aShowDetails = false, $aShowEncodingDetails = false)
    {
        if ($this->iModuleWidth < 0.9) {
            $this->iFontSize = 9;
            $this->iSmallFontSize = 9;
        }
        $this->iEncoder->AddChecksum($this->iUseChecksum);
        $spec = $this->iEncoder->Enc($aData);
        $this->AdjustSpec($spec);
        $s = '';
        $n = count($spec->iBar);
        $w = $this->iModuleWidth;
        $totwidth = $spec->iLeftMargin * $w;
        $n = count($spec->iBar);
        for ($i = 0; $i < $n; ++$i) {
            $b = $spec->iBar[$i];
            $bn = strlen($b[3]);
            for ($j = 0; $j < $bn; ++$j) {
                $wb = substr($b[3], $j, 1) * $w;
                $totwidth += $wb;
            }
        }
        if ($spec->iInterCharModuleSpace) {
            $totwidth += ($n - 2) * $w;
        }
        $totwidth += $spec->iRightMargin * $w;
        $height = $this->iHeight;
        if ($height < round(0.2 * $totwidth)) {
            $height = round(0.2 * $totwidth);
        }
        $startx = $w * $spec->iLeftMargin + $this->ixoffset;
        $ystart = $height + $this->iyoffset;
        if ($spec->iStrokeDataBelow) {
            $ystart += $this->iFontSize;
            $height += 3;
        } elseif ($this->iNoHumanText) {
            $height += 3;
        }
        $inunder = false;
        $under_s = '';
        $under_x = 0;
        $psbar = "";
        $pst = '';
        $x = $startx;
        $details = "%%Symbology specific information:\n%%" . $spec->iInfo . "\n";
        $details .= "\n%%Encoding for individual charcters in choosen symbology\n";
        $details .= "%% # : Char, Length type, Start with bar/space, encoding\n";
        for ($i = 0; $i < $n; ++$i) {
            $b = $spec->iBar[$i];
            $bn = strlen($b[3]);
            if ($aShowEncodingDetails) {
                $details .= "%% " . sprintf("%02d", $i) . " : $b[0], $b[1], $b[2], $b[3]\n";
            }
            if ($b[2] == 0 && !$this->iNoHumanText) {
                if (!$inunder) {
                    $inunder = true;
                    $under_x = $x;
                    $under_s = $b[0];
                } else {
                    $under_s .= $b[0];
                }
            } else {
                if ($inunder) {
                    $inunder = false;
                    if ($under_s != '') {
                        $pst .= '[(' . $under_s . ') ' . (($under_x + $x) / 2) . ' ]';
                    }
                }
            }
            for ($j = 0; $j < $bn; ++$j) {
                $wb = substr($b[3], $j, 1) * $w;
                if ($j % 2 != $b[1]) {
                    $x += $wb / 2;
                    if ($b[2] == 1 || $this->iNoHumanText) {
                        $psbar .= " [" . round($height - $this->iFontSize / 2) . " $x $wb] ";
                    } else {
                        $psbar .= " [" . ($height - $this->iFontSize) . " $x $wb] ";
                    }
                    $x += $wb / 2;
                } else {
                    $x += $wb;
                }
            }
            if ($spec->iInterCharModuleSpace) {
                $x += $w;
            }
            $psbar .= "\n";
        }
        $pstsmall = "";
        if ($spec->iLeftData != "") {
            $pstsmall .= "[($spec->iLeftData) " . ($startx - 2) . "]";
        }
        if ($spec->iRightData != "") {
            $pstsmall .= "[($spec->iRightData) " . ($x + 5) . "]";
        }
        if ($spec->iStrokeDataBelow) {
            $barwidth = $totwidth - $spec->iLeftMargin * $w - $spec->iRightMargin * $w;
            $x = $spec->iLeftMargin * $w + floor($barwidth / 2);
            $pst .= "[($spec->iData) $x ]";
        }
        $ps = ($this->iEPS ? "%!PS-Adobe-3.0 EPSF-3.0\n" : "%!PS-Adobe-3.0\n") . "%%Title: Barcode \"$spec->iData\", encoding: \"$spec->iEncoding\"\n" . "%%Creator: JpGraph Barcode http://www.aditus.nu/jpgraph/\n" . "%%CreationDate: " . date("D j M H:i:s Y",
                time()) . "\n";
        if ($this->iEPS) {
            if ($this->iVertical) {
                $ps .= "%%BoundingBox: 0 0 $ystart $totwidth \n";
            } else {
                $ps .= "%%BoundingBox: 0 0 $totwidth $ystart\n";
            }
        } else {
            $ps .= "%%DocumentPaperSizes: A4\n";
        }
        $ps .= "%%EndComments\n" . "%%BeginProlog\n" . "%%EndProlog\n\n" . "%%Page: 1 1\n\n" . "%%Data: \"$spec->iData\"\n" . "%%Symbology: \"$spec->iEncoding\"\n" . "%%Module width: $this->iModuleWidth pt\n\n";
        if ($aShowEncodingDetails) {
            $ps .= $details . "\n";
        }
        if ($this->iScale != 1) {
            $ps .= "%%Scale barcode\n" . "$this->iScale $this->iScale scale\n\n";
        }
        if ($this->iVertical) {
            $ps .= "%%Rotate barcode 90 degrees\n" . ($ystart + 1) . " 0 translate\n90 rotate\n\n";
        }
        $ps .= "%%Font definition for normal and small fonts\n" . "/f {/Helvetica findfont $this->iFontSize scalefont setfont} def\n" . "/fs {/Helvetica findfont $this->iSmallFontSize scalefont setfont} def\n\n" . "%%Data for bars. Only black bars are defined. \n" . "%%The figures are: [height xpos width]\n";
        $stroke_bars = "{{} forall setlinewidth $ystart moveto -1 mul 0 exch rlineto stroke} forall";
        $ps .= "[ \n" . $psbar . " ] " . $stroke_bars;
        $center_text = "{ {} forall 1 index stringwidth pop 2 div sub 1 $this->iyoffset add moveto show} forall\n";
        $right_text = "{ {} forall 1 index stringwidth pop sub 1 $this->iyoffset add moveto show} forall\n";
        $left_text = "{ {} forall 1 $this->iyoffset add moveto show} forall\n";
        if (!$this->iNoHumanText) {
            $ps .= "\n\n%%Readable text\nf\n[" . $pst . "]\n" . $center_text;
            if ($pstsmall != "") {
                $ps .= "fs\n[" . $pstsmall . "]\n" . $right_text;
            }
        }
        $ps .= "\n\n%%End of barcode for \"$spec->iData\"\n";
        if (!$this->iEPS) {
            $ps .= "\nshowpage\n";
        }
        $ps .= "\n%%Trailer\n";
        if ($aFile != '') {
            $fp = @fopen($aFile, 'w');
            if ($fp) {
                fwrite($fp, $ps);
                fclose($fp);
            } else {
                return false;
            }
        }
        return $ps;
    }
} ?>