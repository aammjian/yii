/**
 * 前端模块配置
 */

require.config({
    paths: {
        'jquery': 'lib/jquery/jquery-2.0.0.min',
        'video-ie8': 'lib/video/video.min',
        'video': 'lib/video/videojs-ie8.min',
        'ckplayer': 'lib/ckplayer/ckplayer'
    },
    shim: {
        'video': ['video-ie8']
    }
});