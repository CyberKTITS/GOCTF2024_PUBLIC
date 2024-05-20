if $(docker build . -t build_apk); then
  # Run Docker container
  docker run --name buildapk -d build_apk sleep infinity

  # Check if build directory exists, create if not
  if [ ! -d "build" ]; then
      echo "Build directory does not exist, creating..."
      mkdir build
  fi

  # Copy APK from Docker container to host
  docker cp buildapk:/opt/project/app/build/outputs/apk/debug/app-debug.apk ./build/piano.apk

  # Stop and remove Docker container
  docker stop buildapk && docker rm buildapk
fi