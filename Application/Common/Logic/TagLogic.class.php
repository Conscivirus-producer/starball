<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/4/17
 * Time: 下午2:19
 */
namespace Common\Logic;
use Common\Model\TagModel;
class TagLogic extends TagModel{
    public function insertTagsForOneItem($itemId, $tagString) {
        $tagArray = split("#", $tagString);
        for ($i = 0; $i < count($tagArray); $i++) {
            if (trim($tagArray[$i]) != ""){
                $data["itemId"] = $itemId;
                $data["type"] = "";
                $data["tagName"] = trim($tagArray[$i]);
                if ($this->add($data) === false) {
                    return false;
                }
            }
        }
        return true;
    }
    public function getTagStringByItemId($itemId) {
        $map["itemId"] = $itemId;
        $tagArray = $this->where($map)->getField("tagName", true);
        return implode("#", $tagArray);
    }
    public function updateTagsForOneItem($itemId, $tagString) {
        $map["itemId"] = $itemId;
        if ($this->where($map)->delete() === false) {
            return false;
        } else {
            return $this->insertTagsForOneItem($itemId, $tagString);
        }
    }
}

?>