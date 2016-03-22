#!/usr/bin/env python

# Written by Limor "Ladyada" Fried for Adafruit Industries, (c) 2015
# This code is released into the public domain

import time
import os
import RPi.GPIO as GPIO

GPIO.setwarnings(False)

GPIO.setmode(GPIO.BCM)


# read SPI data from MCP3008 chip, 8 possible adc's (0 thru 7)
def readadc(adcnum, clockpin, mosipin, misopin, cspin):
        if ((adcnum > 7) or (adcnum < 0)):
                return -1
        GPIO.output(cspin, True)

        GPIO.output(clockpin, False)  # start clock low
        GPIO.output(cspin, False)     # bring CS low

        commandout = adcnum
        commandout |= 0x18  # start bit + single-ended bit
        commandout <<= 3    # we only need to send 5 bits here
        for i in range(5):
                if (commandout & 0x80):
                        GPIO.output(mosipin, True)
                else:
                        GPIO.output(mosipin, False)
                commandout <<= 1
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)

        adcout = 0
        # read in one empty bit, one null bit and 10 ADC bits
        for i in range(12):
                GPIO.output(clockpin, True)
                GPIO.output(clockpin, False)
                adcout <<= 1
                if (GPIO.input(misopin)):
                        adcout |= 0x1

        GPIO.output(cspin, True)
        
        adcout >>= 1       # first bit is 'null' so drop it
        return adcout

# change these as desired - they're the pins connected from the
# SPI port on the ADC to the Cobbler
SPICLK = 18
SPIMISO = 23
SPIMOSI = 24
SPICS = 25

# set up the SPI interface pins
GPIO.setup(SPIMOSI, GPIO.OUT)
GPIO.setup(SPIMISO, GPIO.IN)
GPIO.setup(SPICLK, GPIO.OUT)
GPIO.setup(SPICS, GPIO.OUT)

# 10k trim pot connected to adc #0
selected_adc = 0;

#last_read = 0       # this keeps track of the last potentiometer value
#tolerance = 5       # to keep from being jittery we'll only change
                    # volume when the pot has moved more than 5 'counts'

#while True:
        # we'll assume that the pot didn't move
        #trim_pot_changed = False
# read the analog pin

millis = int(round(time.time() * 1000))

start_time = millis

totalPercent = 0
minValue = 0
maxValue = 0
percentageValue = 0

batteryLevel0 = 100
batteryVoltage0 = 13.3

batteryLevel1 = 90.00
batteryVoltage1 = 12.87

batteryLevel2 = 85.00
batteryVoltage2 = 12.85

batteryLevel3 = 80.00
batteryVoltage3 = 12.83

batteryLevel4 = 75.00
batteryVoltage4 = 12.81

batteryLevel5 = 70.00
batteryVoltage5 = 12.78

batteryLevel6 = 65.00
batteryVoltage6 = 12.68

batteryLevel7 = 60.00
batteryVoltage7 = 12.63

batteryLevel8 = 55.00
batteryVoltage8 = 12.60

batteryLevel9 = 50.00
batteryVoltage9 = 12.57

batteryLevel10 = 40.00
batteryVoltage10 = 12.50

batteryLevel11 = 35.00
batteryVoltage11 = 12.29

batteryLevel12 = 30.00
batteryVoltage12 = 12.22

batteryLevel13 = 20.00
batteryVoltage13 = 12.10

batteryLevel14 = 0.00
batteryVoltage14 = 12.00

while ( (millis - start_time) < 2000 ):
	  
	readValue = readadc(selected_adc, SPICLK, SPIMOSI, SPIMISO, SPICS)

	if (readValue > maxValue):
           	#/*record the maximum sensor value*/
           	maxValue = readValue
      	if (readValue < minValue):
           	#/*record the maximum sensor value*/
           	minValue = readValue
	millis = int(round(time.time() * 1000))
	result1 = ((maxValue - minValue) ) # * 12.0) / 1024


#print result1

batteryVoltage = .96 * result1 * (3.3/1023.0) / (33.0/(33.0+100.0))

#print "percentage", batteryVoltage

if (batteryVoltage >= batteryVoltage0):
	percentageValue = 100

elif (batteryVoltage < batteryVoltage0 and batteryVoltage > batteryVoltage1) :
	percentageValue = (batteryLevel0 - batteryLevel1) * (batteryVoltage - batteryVoltage1)/(batteryVoltage0 - batteryVoltage1) + batteryLevel1

elif (batteryVoltage <= batteryVoltage1 and batteryVoltage > batteryVoltage2) :
	percentageValue = (batteryLevel1 - batteryLevel2) * (batteryVoltage - batteryVoltage2)/(batteryVoltage1 - batteryVoltage2) + batteryLevel2

elif (batteryVoltage <= batteryVoltage2 and batteryVoltage > batteryVoltage3) :
        percentageValue = (batteryLevel2 - batteryLevel3) * (batteryVoltage - batteryVoltage3)/(batteryVoltage2 - batteryVoltage3) + batteryLevel3

elif (batteryVoltage <= batteryVoltage3 and batteryVoltage > batteryVoltage4) :
        percentageValue = (batteryLevel3 - batteryLevel4) * (batteryVoltage - batteryVoltage4)/(batteryVoltage3 - batteryVoltage4) + batteryLevel4

elif (batteryVoltage <= batteryVoltage4 and batteryVoltage > batteryVoltage5) :
        percentageValue = (batteryLevel4 - batteryLevel5) * (batteryVoltage - batteryVoltage5)/(batteryVoltage4 - batteryVoltage5) + batteryLevel5

elif (batteryVoltage <= batteryVoltage5 and batteryVoltage > batteryVoltage6) :
        percentageValue = (batteryLevel5 - batteryLevel6) * (batteryVoltage - batteryVoltage6)/(batteryVoltage5 - batteryVoltage6) + batteryLevel6

elif (batteryVoltage <= batteryVoltage6 and batteryVoltage > batteryVoltage7) :
        percentageValue = (batteryLevel6 - batteryLevel7) * (batteryVoltage - batteryVoltage7)/(batteryVoltage6 - batteryVoltage7) + batteryLevel7

elif (batteryVoltage <= batteryVoltage7 and batteryVoltage > batteryVoltage8) :
        percentageValue = (batteryLevel7 - batteryLevel8) * (batteryVoltage - batteryVoltage8)/(batteryVoltage7 - batteryVoltage8) + batteryLevel8

elif (batteryVoltage <= batteryVoltage8 and batteryVoltage > batteryVoltage9) :
        percentageValue = (batteryLevel8 - batteryLevel9) * (batteryVoltage - batteryVoltage9)/(batteryVoltage8 - batteryVoltage9) + batteryLevel9

elif (batteryVoltage <= batteryVoltage9 and batteryVoltage > batteryVoltage10) :
        percentageValue = (batteryLevel9 - batteryLevel10) * (batteryVoltage - batteryVoltage10)/(batteryVoltage9 - batteryVoltage10) + batteryLevel10

elif (batteryVoltage <= batteryVoltage10 and batteryVoltage > batteryVoltage11) :
        percentageValue = (batteryLevel10 - batteryLevel11) * (batteryVoltage - batteryVoltage11)/(batteryVoltage10 - batteryVoltage11) + batteryLevel11

elif (batteryVoltage <= batteryVoltage11 and batteryVoltage > batteryVoltage12) :
        percentageValue = (batteryLevel11 - batteryLevel12) * (batteryVoltage - batteryVoltage12)/(batteryVoltage11 - batteryVoltage12) + batteryLevel12

elif (batteryVoltage <= batteryVoltage12 and batteryVoltage > batteryVoltage13) :
        percentageValue = (batteryLevel12 - batteryLevel13) * (batteryVoltage - batteryVoltage13)/(batteryVoltage12 - batteryVoltage13) + batteryLevel13

elif (batteryVoltage <= batteryVoltage13 and batteryVoltage > batteryVoltage14) :
	percentageValue = (batteryLevel13 - batteryLevel14) * (batteryVoltage - batteryVoltage14)/(batteryVoltage13 - batteryVoltage14) + batteryLevel14

elif (batteryVoltage <= batteryVoltage14) :
	percentageValue = 0

#    if percentageValue >= maxVal:
#        maxVal = percentageValue
#    elif percentageValue <= minVal:
#        minVal = percentageValue;

#rangeDiff = maxVal - minVal;

# print "maxVal", maxVal
# print "minVal", minVal
# print rangeDiff

print (percentageValue/100)