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
    "source": "rtsp:\/\/user:pass@192.168.0.123:554\/live\/ch01_0",
    "hls": "stream.m3u8"
  }
}
```

HLS is newly created stream playlist.

## Stop streaming

Send POST to the `stopStream` endpoint with body containing stream identifier:

    http -f post http://stream.adeira.loc/stopStream identifier=00000000-0000-0000-0000-000000000001

## Stream processing

To consume all registered streams you should run this command in CLI:

    bin/consumeStreams

It uses [FFmpeg](https://johnvansickle.com/ffmpeg/) under the hood so it **must be installed** otherwise it won't work.

## Errors

Errors are returned in format similar to GraphQL. There is few examples:

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

```json
{
  "errors": [
    {
      "message": "Internal Server Error"
    }
  ]
}
```
