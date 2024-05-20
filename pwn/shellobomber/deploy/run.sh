#!/bin/bash
socat tcp-listen:1557,reuseaddr,fork exec:"./shellobomber";
