FROM gradle:8.7.0-jdk17

RUN mkdir /opt/project
COPY ./src/  /opt/project
WORKDIR /opt/project

ARG ANDROID_SDK_VERSION=8092744
ENV ANDROID_SDK_ROOT /opt/android-sdk
RUN mkdir -p ${ANDROID_SDK_ROOT}/cmdline-tools && \
    wget https://dl.google.com/android/repository/commandlinetools-linux-${ANDROID_SDK_VERSION}_latest.zip && \
    unzip *tools*linux*.zip -d ${ANDROID_SDK_ROOT}/cmdline-tools && \
    mv ${ANDROID_SDK_ROOT}/cmdline-tools/cmdline-tools ${ANDROID_SDK_ROOT}/cmdline-tools/tools && \
    rm *tools*linux*.zip
    
ENV PATH ${PATH}:${ANDROID_SDK_ROOT}/cmdline-tools/latest/bin:${ANDROID_SDK_ROOT}/cmdline-tools/tools/bin:${ANDROID_SDK_ROOT}/platform-tools:${ANDROID_SDK_ROOT}/emulator
RUN yes | sdkmanager --licenses && \
		sdkmanager "platforms;android-29" "platforms;android-30" "platforms;android-31" "build-tools;29.0.2" && \
		cd /opt/project
RUN gradle build 
