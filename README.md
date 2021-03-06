[![Build Status](https://travis-ci.org/adeira/connector-stream.svg?branch=master)](https://travis-ci.org/adeira/connector-stream)

This is small microservice for managing video streaming. At this moment it converts RTSP stream to the HLS playlist witm `.m3u8` extension.

## Start streaming

Send POST to the `startStream` endpoint with body containing stream source ([httpie](https://github.com/jkbrzt/httpie) example):

```
http -f post http://stream.adeira.loc/startStream source=rtsp://user:pass@192.168.0.123:554/live/ch01_0
```

Expected response is:

```json
{
  "data": {
    "source": "rtsp://user:pass@192.168.0.123:554/live/ch01_0",
    "id": "678afdcb-d0de-4b79-b522-f1d22e3b2959",
    "hls": "/hls/dwGC3oTFrqZZWvMFYhrC8d/stream.m3u8"
  }
}
```

HLS is newly created stream playlist.

## Stop streaming

Send POST to the `stopStream` endpoint with body containing stream identifier:

    http -f post http://stream.adeira.loc/stopStream identifier=678afdcb-d0de-4b79-b522-f1d22e3b2959

Response:

```json
{
  "data": {
    "identifier": "678afdcb-d0de-4b79-b522-f1d22e3b2959"
  }
}
```

Possible errors:

```json
{
  "errors": [
    {
      "message": "Stream with identifier '00000000-0000-0000-0000-000000000001' is not registered!"
    }
  ]
}
```

```json
{
  "errors": [
    {
      "message": "Identifier must be in valid UUID format version 4."
    }
  ]
}
```

## Stream processing

To consume all registered streams you should run this command in CLI:

    bin/consumeStreams

It uses [System program execution](http://php.net/manual/en/book.exec.php) under the hood so it **must be enabled** otherwise it won't work. It also uses FFmpeg but it's distributed with the code so you don't have to care about it.

## Server error

Errors are returned in format similar to GraphQL. There is example of server error:

```json
{
  "errors": [
    {
      "message": "Internal Server Error"
    }
  ]
}
```

(returns code 500)
