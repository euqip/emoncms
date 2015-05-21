<?php

  class ProcessArg {
    const VALUE = 0;
    const INPUTID = 1;
    const FEEDID = 2;
    const NONE = 3;
    const TEXT = 4;
    const SCHEDULEID = 5;
  }

  class DataType {
    const UNDEFINED = 0;
    const REALTIME = 1;
    const DAILY = 2;
    const HISTOGRAM = 3;
  }

  class Engine {
    const MYSQL = 0;
    const TIMESTORE = 1;
    const PHPTIMESERIES = 2;
    const GRAPHITE = 3;
    const PHPTIMESTORE = 4;
    const PHPFINA = 5;
    const PHPFIWA = 6;
  }

  class Role{
      const LAMBDA   = 0; // can see published date from same organisation, or public data
      const SYSADMIN = 1; // can manage, add and delete users and orgs independantly from his org
      const ORGADMIN = 3; // can manage add and delete users from same org
      const VIEWER   = 4; // can see dashboard from same org
      const DESIGNER = 5; // can create / change dashboard from same org
  }