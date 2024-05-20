@echo off
setlocal

for /F %%i in ('docker build . -t build_apk') do (
  if "%%i" equ "Successfully built" (
    rem Run Docker container
    docker run --name buildapk -d build_apk sleep infinity

    rem Check if build directory exists, create if not
    if not exist "build" (
      echo Build directory does not exist, creating...
      mkdir build
    )

    rem Copy APK from Docker container to host
    docker cp buildapk:/opt/project/app/build/outputs/apk/debug/app-debug.apk ./build/piano.apk

    rem Stop and remove Docker container
    docker stop buildapk && docker rm buildapk
  )
)

endlocal
