<?php
class RestClient
{
    private $_url;
    public function setUrl ($pUrl)
    {
        $this->_url = $pUrl;
        return $this;
    }

    public function get ($pParams = array())
    {
        return $this->_launch($this->_makeUrl($pParams),
            $this->_createContext('GET'));
    }

    public function post ($pPostParams=array(), $pGetParams = array())
    {
        return $this->_launch($this->_makeUrl($pGetParams),
            $this->_createContext('POST', $pPostParams));
    }

    public function put ($pContent = null, $pGetParams = array())
    {
        return $this->_launch($this->_makeUrl($pGetParams),
            $this->_createContext('PUT', $pContent));
    }

    public function delete ($pContent = null, $pGetParams = array())
    {
        return $this->_launch($this->_makeUrl($pGetParams),
            $this->_createContext('DELETE', $pContent));
    }

    protected function _createContext($pMethod, $pContent = null)
    {
        $opts = array(
            'http'=>array(
                'method'=>$pMethod,
                'header'=>'Content-type: application/x-www-form-urlencoded',
            )
        );
        if ($pContent !== null){
            if (is_array($pContent)){
                $pContent = http_build_query($pContent);
            }
            $opts['http']['content'] = $pContent;
        }
        return stream_context_create($opts);
    }

    protected function _makeUrl($pParams)
    {
        return $this->_url
        .(strpos($this->_url, '?') ? '' : '?')
        .http_build_query($pParams);
    }

    protected function _launch ($pUrl, $context)
    {
        if (($stream = fopen($pUrl, 'r', false, $context)) !== false){
            $content = stream_get_contents($stream);
            $header = stream_get_meta_data($stream);
            fclose($stream);
            return array('content'=>$content, 'header'=>$header);
        }else{
            return false;
        }
    }
}