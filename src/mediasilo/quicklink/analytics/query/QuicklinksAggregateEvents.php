<?php

namespace mediasilo\quicklink\analytics\query;

use mediasilo\quicklink\analytics\query\IQuery;

class QuicklinksAggregateEvents implements IQuery {

    private $quicklinkIds;

    function __construct($quicklinkIds) {
        $this->quicklinkIds = $quicklinkIds;
    }

    public function getQuery() {
        $comma_separated = '"'.implode('","', $this->quicklinkIds).'"';

        $query = '{
            "query": {
                "filtered": {
                    "query": {
                        "match_all": {
                        }
                    },
                    "filter": {
                        "terms": {
                            "data.quicklinkId": ['.$comma_separated.']
                        }
                    }
                }
            },
            "sort" : {"created" : "desc"},
            "aggs" : {
                "quicklinks" : {
                    "terms" : { "field" : "data.quicklinkId"},
                    "aggs" : {
                        "events" : {
                            "terms" : {"field" : "_type"},
                            "aggs" : {
                                "unique_visitors" : {
                                    "terms" : {"field" : "visitor.id" }
                                }
                            }
                        }
                    }
                }
            }
        }';

        return $query;
    }
} 