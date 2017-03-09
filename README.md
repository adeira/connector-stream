[![Build Status](https://travis-ci.org/adeira/connector-stream.svg?branch=master)](https://travis-ci.org/adeira/connector-stream)

If you want to start streaming just send POST to the `consumeStream` with body containing stream source ([httpie](https://github.com/jkbrzt/httpie) example):

```
http -f post http://stream.adeira.loc/consumeStream source=rtsp://test:test@192.168.0.123:554/live/ch01_0
```

Expected response is:

```json
{
  "source": "rtsp:\/\/test:test@192.168.0.123:554\/live\/ch01_0",
  "hls": "stream.m3u8"
}
```

HLS is newly created stream playlist.

https://johnvansickle.com/ffmpeg/
