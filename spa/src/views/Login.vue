<template>
  <v-app app>
    <div class="back" @click="goBack" v-if="!loginForm && !loading">
      <svg width="16" height="28" viewBox="0 0 16 28" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M16 4L6 14L16 24L14 28L0 14L14 0L16 4Z" fill="#9DCCFF"/>
      </svg>
      <span>{{$t('auth.back')}}</span>
    </div>

    <NavbarEmpty />

    <template v-if="loading === false">
      <form class="login-form"  @submit.prevent="login()" :class="{show: animateTrigger}" v-if="loginForm">
        <img class="logo" src="@/assets/icons/smallLogo.svg" alt="">
        <div class="form-title">{{$t('auth.EasySellAuthorization')}}</div>
        <div class="form-description">{{$t('auth.authDescription')}}</div>
        <social-auth 
          @socialError="socialError" 
          @success="successAuth"
          type="login"
        >
        </social-auth>
        <div class="login-item first" :class="{show: animateTrigger}">
          <!-- <span class="phone-placeholder" v-if="phonePlaceholder">+38</span> -->
            <small
                v-if="$v.email.$dirty && !$v.email.required && !phonePlaceholder"
                class="input-invalid"
            >{{$t('auth.enterEmailOrPhone')}}</small>
            <small
                v-if="$v.email.$dirty && !$v.email.email && !phonePlaceholder"
                class="input-invalid"
            >{{$t('auth.emailNotCorrect')}}</small>
            <small class="input-invalid" v-if="fieldErrors.phoneFlag === true">
              {{ fieldErrors.phoneText[0] ? fieldErrors.phoneText[0] : '' }}
            </small>
            <small class="input-invalid" v-if="fieldErrors.emailFlag === true">
              {{ fieldErrors.emailText }}
            </small>
            <span class="label" v-if="fieldErrors.labelFlag">
              {{$t('auth.numberOrEmail')}}
            </span>
          <input
              class="login-email"
              v-model="email"
              :error-messages="emailErrors"
              :class="{errorInput: emailErrors.length}"
              label="E-mail"
              @input="checkRegisterValue"
          />
          <!-- loginPhone: phonePlaceholder -->
        </div>
        <div class="login-item second" :class="{show: animateTrigger}">
          <small
              v-if="$v.password.$dirty && !$v.password.required"
              class="input-invalid"
          >{{$t('auth.enterPassword')}}</small>
          <span class="label" v-else>{{$t('auth.enterPassword')}}</span>
          <input
              class="login-password"
              v-model="password"
              :error-messages="passwordErrors"
              :class="{errorInput: passwordErrors.length}"
              label="Password"
              :type="type"
              ref="password"
              @input="$v.password.$touch()"
              @blur="$v.password.$touch()"
          />
          <div class="showPass" v-show="password.length" @click="showPassword">
            <svg v-if="!showPass" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M9 0C7.07143 0 5.4 0.533333 3.98571 1.6L5.01429 2.53333C6.17143 1.73333 7.45714 1.33333 9 1.33333C13.2429 1.33333 16.7143 4.93333 16.7143 9.33333H18C18 4.13333 14.0143 0 9 0ZM0 1.33333L2.05714 3.33333C0.771428 4.93333 0 7.06667 0 9.33333H1.28571C1.28571 7.33333 1.92857 5.6 3.08571 4.26667L5.91429 6.93333C5.4 7.6 5.14286 8.4 5.14286 9.33333C5.14286 11.6 6.81429 13.3333 9 13.3333C10.0286 13.3333 10.9286 12.9333 11.5714 12.2667L15.4286 16L16.3286 15.0667L0.9 0.4L0 1.33333ZM6.81429 7.86667L10.5429 11.4667C10.1571 11.7333 9.64286 12 9 12C7.58571 12 6.42857 10.8 6.42857 9.33333C6.42857 8.8 6.55714 8.26667 6.81429 7.86667ZM12.8571 10L11.5714 8.8C11.3143 7.73333 10.4143 6.8 9.25714 6.66667L7.97143 5.46667C8.35714 5.33333 8.61429 5.33333 9 5.33333C11.1857 5.33333 12.8571 7.06667 12.8571 9.33333V10Z" fill="#9DCCFF"/>
            </svg>
            <svg v-if="showPass" width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M0 9.1C0 4.03 3.98571 0 9 0C14.0143 0 18 4.03 18 9.1H16.7143C16.7143 4.81 13.2429 1.3 9 1.3C4.75714 1.3 1.28571 4.81 1.28571 9.1H0ZM5.14286 9.1C5.14286 6.89 6.81429 5.2 9 5.2C11.1857 5.2 12.8571 6.89 12.8571 9.1C12.8571 11.31 11.1857 13 9 13C6.81429 13 5.14286 11.31 5.14286 9.1ZM6.42857 9.1C6.42857 10.53 7.58571 11.7 9 11.7C10.4143 11.7 11.5714 10.53 11.5714 9.1C11.5714 7.67 10.4143 6.5 9 6.5C7.58571 6.5 6.42857 7.67 6.42857 9.1Z" fill="#5893D4"/>
            </svg>
          </div>
        </div>
        <span class="label label-error" v-if="errored">{{$t('auth.authError')}}</span>
        <div class="forgot-pass" :class="{show: animateTrigger}">
          <router-link to="/register" class="registration">{{$t('auth.register')}}</router-link>
          <span @click="reminderSwitch">{{$t('auth.forgotPassword')}}</span>
        </div>
        <button 
          type="sumbit"
          class="base-button base-button--accent"
          @submit.prevent="login"
          @click="loader = 'loading'"
          :class="{show: animateTrigger}"
          :loading="loading"
          :disabled="!password && !email"
        >
          {{$t('auth.enter')}}
        </button>
      </form>
    </template>
    <template v-else>
      <Preloader/>
    </template>

    <!-- Password reminder form -->

    <form @submit.prevent="reminderSubmit" class="login-form password-reminder show" v-if="reminderForm && !loading">
      <img class="logo" src="@/assets/icons/smallLogo.svg" alt="">
      <div class="form-title" v-if="!enterCode">{{$t('auth.passwordReminderTitle')}}</div>
      <div class="form-title" v-if="enterCode">{{$t('auth.authText')}}</div>
      <div class="reminder" v-if="!enterCode">
        <div class="login-item first show" v-if="!reminderSent">
          <small
              v-if="$v.email.$dirty && !$v.email.required && !phonePlaceholder"
              class="input-invalid"
          >{{$t('auth.enterEmailOrPhone')}}</small>
          <small
              v-if="$v.email.$dirty && !$v.email.email && !phonePlaceholder"
              class="input-invalid"
          >{{$t('auth.emailNotCorrect')}}</small>
          <small class="input-invalid" v-if="fieldErrors.phoneFlag === true">
            {{ fieldErrors.phoneText[0] }}
          </small>
          <small class="input-invalid" v-if="fieldErrors.emailFlag === true">
            {{ fieldErrors.emailText }}
          </small>
          <span class="label" v-if="fieldErrors.labelFlag">
            {{$t('auth.numberOrEmail')}}
          </span>
          <div class="login-item-field">
            <input
                class="login-email"
                v-model.trim="email"
                :error-messages="emailErrors"
                :class="{errorInput: emailErrors.length || fieldErrors.emailFlag || fieldErrors.phoneFlag}"
                label="E-mail"
                @input="checkRegisterValue"
            />
            <button 
              :class="{'show': email.length !== 0}"
              class="clear" 
              type="button" 
              @click="clear"
            >
              <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.81642 0.183582C9.87461 0.241628 9.92078 0.310585 9.95228 0.386501C9.98378 0.462418 10 0.543805 10 0.625998C10 0.708191 9.98378 0.789578 9.95228 0.865494C9.92078 0.941411 9.87461 1.01037 9.81642 1.06841L1.06809 9.81675C0.95075 9.93408 0.791608 10 0.62567 10C0.459732 10 0.30059 9.93408 0.183254 9.81675C0.0659185 9.69941 1.23634e-09 9.54027 0 9.37433C-1.23634e-09 9.20839 0.0659185 9.04925 0.183254 8.93191L8.93159 0.183582C8.98963 0.125389 9.05859 0.0792193 9.13451 0.0477172C9.21042 0.0162152 9.29181 0 9.374 0C9.4562 0 9.53758 0.0162152 9.6135 0.0477172C9.68942 0.0792193 9.75837 0.125389 9.81642 0.183582Z" fill="#9DCCFF"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.183582 0.183582C0.125389 0.241628 0.0792193 0.310585 0.0477172 0.386501C0.0162152 0.462418 0 0.543805 0 0.625998C0 0.708191 0.0162152 0.789578 0.0477172 0.865494C0.0792193 0.941411 0.125389 1.01037 0.183582 1.06841L8.93191 9.81675C9.04925 9.93408 9.20839 10 9.37433 10C9.54027 10 9.69941 9.93408 9.81675 9.81675C9.93408 9.69941 10 9.54027 10 9.37433C10 9.20839 9.93408 9.04925 9.81675 8.93191L1.06841 0.183582C1.01037 0.125389 0.941411 0.0792193 0.865494 0.0477172C0.789578 0.0162152 0.708191 0 0.625998 0C0.543805 0 0.462418 0.0162152 0.386501 0.0477172C0.310585 0.0792193 0.241628 0.125389 0.183582 0.183582Z" fill="#9DCCFF"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="reminder-content" v-if="reminderSent">
          <div class="reminder-content">
            <p>{{$t('auth.reminderContent')}}</p>
            <p 
              v-if="!resendState"
              :class="{'resend': true, 'resend--disabled': resendState}" 
              @click="resend('email')"
            >
                Не пришло письмо / sms<span style="display: block;">{{$t('auth.resendLink')}}</span>
            </p>
            <countdown-timer 
              v-else
              @end="endTimer"
            >
              Возможность отправить письмо повторно будет через:
            </countdown-timer>
          </div>
          <!-- <p>{{$t('auth.reminderContent')}}</p>
            <p 
              :class="{'resend': true, 'resend--disabled': resendState}" 
              @click="resend('email')"
            >
              <template v-if="!resendState">
                Не пришло письмо / sms<span style="display: block;">{{$t('auth.resendLink')}}</span>
              </template>
              <template v-else>
                Письмо отправлено на почту
              </template>
            </p> -->
        </div>
      </div>
      <div class="login-item first show" v-if="enterCode">
        <!-- <span class="label">{{$t('auth.enterCode')}}</span>
        <input
            class="login-email"
            v-model="number"
            type="number"
        /> -->
          <small class="input-invalid" v-if="isCode">
            Неверный пин-код
          </small>
          <span v-if="!isCode" class="label">{{$t('auth.codeNumberText')}}</span>
          <div class="login-form-code">
            <input ref="firstCodeField" @keyup="checkFieldHandler(1)" :class='{"code-field-error": isCode}' class="code-field" type="text" v-model.trim="code.first" v-mask="'#'" placeholder="X">
            <input ref="secondCodeField" @keyup="checkFieldHandler(2)" :class='{"code-field-error": isCode}' class="code-field" type="text" v-model.trim="code.second" v-mask="'#'" placeholder="X">
            <input ref="thirdCodeField" @keyup="checkFieldHandler(3)" :class='{"code-field-error": isCode}' class="code-field" type="text" v-model.trim="code.third" v-mask="'#'" placeholder="X">
            <input ref="fourCodeField" @keyup="checkFieldHandler(4)" :class='{"code-field-error": isCode}' class="code-field" type="text" v-model.trim="code.four" v-mask="'#'" placeholder="X">
          </div>
          <button 
              type="button"
              class="base-button base-button--accent"
              :class="{show: animateTrigger}"
              :disabled="!code.first || !code.second || !code.third || !code.four"
              @click="sendCode"
            >
              {{$t('auth.send')}}
          </button>
          <div class="forgot-pass have-account" :class="{show: animateTrigger}" v-if="!resendState">
            <span>{{$t('auth.resendLetter')}}</span>
            <a 
              href="#"
              :class="{'registration': true, 'resend': true, 'resend--disabled': resendState}" 
              @click.prevent="resend('phone')"
            >
              <template v-if="!resendState">
                {{$t('auth.resendLink')}}
              </template>
            </a>
          </div>
          <countdown-timer 
              v-else
              @end="endTimer"
              style="margin-top: 15px;"
            >
              Возможность отправить код повторно будет через:
          </countdown-timer>
      </div>
      <button :disabled="!email.length" v-if="!reminderSent && !enterCode" type="sumbit" class="base-button base-button--accent show">{{$t('auth.send')}}</button>
      <!-- <button type="button" v-if="reminderSent" @click="goEnter" class="base-button base-button--accent show">{{$t('auth.enter')}}</button> -->
      <button 
        v-if="reminderSent"
        type="button"
        class="base-button base-button--accent"
        @click="goBack"
        :class="{show: animateTrigger}"
      >
        {{$t('auth.enter')}}
      </button>
    </form>

    <!-- <form class="login-form form-code show" v-if="reminderForm">
      <img class="logo" src="@/assets/icons/smallLogo.svg" alt="">
      <div class="form-title">{{$t('auth.authText')}}</div>
      <span class="label">{{$t('auth.codeNumberText')}}</span>
      <div class="login-form-code">
        <input class="code-field" type="text" v-model="code.first" v-mask="'#'" placeholder="X">
        <input class="code-field" type="text" v-model="code.second" v-mask="'#'" placeholder="X">
        <input class="code-field" type="text" v-model="code.third" v-mask="'#'" placeholder="X">
        <input class="code-field" type="text" v-model="code.four" v-mask="'#'" placeholder="X">
      </div>
      <button 
          type="sumbit"
          class="base-button base-button--accent"
          :class="{show: animateTrigger}"
          :disabled="!code.first || !code.second || !code.third || !code.four"
        >
          {{$t('auth.send')}}
        </button>
        <div class="forgot-pass have-account" :class="{show: animateTrigger}">
          <span>{{$t('auth.resendLetter')}}</span>
          <a href="#" class="registration">{{$t('auth.resendLink')}}</a>
        </div>
    </form> -->

    <div class="anim anim-1" :class="{show : anim1}" v-if="loading === false">
      <svg class="svg3" :class="{show: svgScale}" width="493" height="120" viewBox="0 0 493 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect opacity="0.8" x="124.244" y="38.046" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.8" x="-1.71289" y="0.756104" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.8" x="196.338" y="85.2799" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.8" x="38.0625" y="55.448" width="14.916" height="14.916" fill="white"/>
        <rect opacity="0.2" x="83.6387" y="25.6161" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.2" x="137.502" y="85.2799" width="14.916" height="14.916" fill="white"/>
        <rect opacity="0.2" x="319.809" y="85.2799" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.2" x="375.33" y="110.14" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.2" x="443.279" y="80.308" width="14.916" height="14.916" fill="white"/>
        <rect opacity="0.1" x="484.713" y="105.997" width="8.28666" height="8.28666" fill="white"/>
        <rect opacity="0.5" x="161.533" y="13.186" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.5" x="263.461" y="103.511" width="24.86" height="24.86" fill="white"/>
        <rect opacity="0.5" x="201.309" y="48.8187" width="14.916" height="14.916" fill="white"/>
        <rect opacity="0.5" x="61.2656" y="102.682" width="14.916" height="14.916" fill="white"/>
        <rect opacity="0.2" x="-10" y="90.252" width="24.86" height="24.86" fill="white"/>
      </svg>
      <svg class="svg4" :class="{show: svgScale}" width="493" height="120" viewBox="0 0 493 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect opacity="0.8" width="24.86" height="24.86" transform="matrix(-1 0 0 1 368.756 38.046)" fill="white"/>
        <rect opacity="0.8" width="24.86" height="24.86" transform="matrix(-1 0 0 1 494.713 0.756104)" fill="white"/>
        <rect opacity="0.8" width="24.86" height="24.86" transform="matrix(-1 0 0 1 296.662 85.2799)" fill="white"/>
        <rect opacity="0.8" width="14.916" height="14.916" transform="matrix(-1 0 0 1 454.938 55.448)" fill="white"/>
        <rect opacity="0.2" width="24.86" height="24.86" transform="matrix(-1 0 0 1 409.361 25.6161)" fill="white"/>
        <rect opacity="0.2" width="14.916" height="14.916" transform="matrix(-1 0 0 1 355.498 85.2799)" fill="white"/>
        <rect opacity="0.2" width="24.86" height="24.86" transform="matrix(-1 0 0 1 173.191 85.2799)" fill="white"/>
        <rect opacity="0.2" width="24.86" height="24.86" transform="matrix(-1 0 0 1 117.67 110.14)" fill="white"/>
        <rect opacity="0.2" width="14.916" height="14.916" transform="matrix(-1 0 0 1 49.7207 80.308)" fill="white"/>
        <rect opacity="0.1" width="8.28666" height="8.28666" transform="matrix(-1 0 0 1 8.28711 105.997)" fill="white"/>
        <rect opacity="0.5" width="24.86" height="24.86" transform="matrix(-1 0 0 1 331.467 13.186)" fill="white"/>
        <rect opacity="0.5" width="24.86" height="24.86" transform="matrix(-1 0 0 1 229.539 103.511)" fill="white"/>
        <rect opacity="0.5" width="14.916" height="14.916" transform="matrix(-1 0 0 1 291.691 48.8187)" fill="white"/>
        <rect opacity="0.5" width="14.916" height="14.916" transform="matrix(-1 0 0 1 431.734 102.682)" fill="white"/>
        <rect opacity="0.2" width="24.86" height="24.86" transform="matrix(-1 0 0 1 503 90.252)" fill="white"/>
      </svg>
      <svg width="1920" height="424" viewBox="0 0 1920 424" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0.296564C0 0.296564 219.063 119.471 367 129.445C607.73 145.675 702.403 2.29063 944.5 0.296536C1176.11 -1.61122 1306.38 146.004 1536.5 129.445C1682.38 118.947 1920 0.296564 1920 0.296564V423.297H0V0.296564Z" fill="url(#paint0_linear)"/>
        <defs>
          <linearGradient id="paint0_linear" x1="960" y1="0.296509" x2="960" y2="343.296" gradientUnits="userSpaceOnUse">
            <stop stop-color="#5893D4"/>
            <stop offset="1" stop-color="#8DB7E4"/>
          </linearGradient>
        </defs>
      </svg>
    </div>
    <div class="anim anim-2" :class="{show : anim2}" v-if="loading === false">
      <svg class="svg1" :class="{show: svgScale}" width="453" height="131" viewBox="0 0 453 131" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g opacity="0.6">
          <rect opacity="0.2" x="316" y="88" width="18" height="18" fill="white"/>
          <rect opacity="0.2" x="223" y="92" width="18" height="18" fill="white"/>
          <rect opacity="0.2" y="57" width="18" height="18" fill="white"/>
          <rect opacity="0.3" x="159" y="52" width="15" height="15" fill="white"/>
          <rect opacity="0.1" x="103" y="93" width="15" height="15" fill="white"/>
          <rect opacity="0.1" x="73" width="15" height="15" fill="white"/>
          <rect opacity="0.1" x="386" y="121" width="10" height="10" fill="white"/>
          <rect opacity="0.3" x="443" y="107" width="10" height="10" fill="white"/>
          <rect opacity="0.1" x="264" y="70" width="10" height="10" fill="white"/>
        </g>
      </svg>
      <svg class="svg2" :class="{show: svgScale}" width="453" height="131" viewBox="0 0 453 131" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g opacity="0.6">
          <rect opacity="0.2" width="18" height="18" transform="matrix(-1 0 0 1 137 88)" fill="white"/>
          <rect opacity="0.2" width="18" height="18" transform="matrix(-1 0 0 1 230 92)" fill="white"/>
          <rect opacity="0.2" width="18" height="18" transform="matrix(-1 0 0 1 453 57)" fill="white"/>
          <rect opacity="0.3" width="15" height="15" transform="matrix(-1 0 0 1 294 52)" fill="white"/>
          <rect opacity="0.1" width="15" height="15" transform="matrix(-1 0 0 1 350 93)" fill="white"/>
          <rect opacity="0.1" width="15" height="15" transform="matrix(-1 0 0 1 380 0)" fill="white"/>
          <rect opacity="0.1" width="10" height="10" transform="matrix(-1 0 0 1 67 121)" fill="white"/>
          <rect opacity="0.3" width="10" height="10" transform="matrix(-1 0 0 1 10 107)" fill="white"/>
          <rect opacity="0.1" width="10" height="10" transform="matrix(-1 0 0 1 189 70)" fill="white"/>
        </g>
      </svg>
      <svg width="1920" height="495" viewBox="0 0 1920 495" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 22.8926C0 22.8926 162.063 -9.07056 310 2.89258C550.73 22.3596 734.403 135.023 976.5 132.631C1208.11 130.343 1392.88 22.7546 1623 2.89258C1768.88 -9.69832 1920 22.8926 1920 22.8926V494.297H0V22.8926Z" fill="url(#paint0_linear)"/>
        <defs>
          <linearGradient id="paint0_linear" x1="960" y1="-2.70349" x2="960" y2="414.296" gradientUnits="userSpaceOnUse">
            <stop stop-color="#5893D4"/>
            <stop offset="1" stop-color="#8DB7E4"/>
          </linearGradient>
        </defs>
      </svg>
    </div>
  </v-app>
</template>

<script>
// import axios from 'axios'
import { validationMixin } from 'vuelidate'
import { required, email  } from 'vuelidate/lib/validators'
import NavbarEmpty from "@/components/app/NavbarEmpty"
import SocialAuth from "@/components/app/auth/SocialAuth"
import { mapGetters } from 'vuex'
import Preloader from "@/components/ui/Preloader"
import CountdownTimer from "@/components/ui/CountdownTimer"
import { PROTOCOL } from '@/domain'

export default {
  name: 'login',
  mixins: [validationMixin],
  validations: {
    email: { required, email },
    password: {required},
  },
  components: {
    NavbarEmpty,
    SocialAuth,
    Preloader,
    CountdownTimer
  },
  data: () => ({
    name: '',
    email: '',
    password: '',
    number: '',
    type: 'password',
    loader: null,
    loading: false,
    loginForm: true,
    reminderForm: false,
    animateTrigger: false,
    animateTrigger2: false,
    reminderSent: false,
    enterCode: false,
    anim1: false,
    anim2: false,
    svgScale: false,
    errored: false,
    showPass: false,
    phonePlaceholder: false,
    resendState: false,
    fieldErrors: {
      emailFlag: false,
      phoneFlag: false,
      emailText: "",
      phoneText: "text",
      labelFlag: true,
    },
    code: {
      first: null,
      second: null,
      third: null,
      four: null
    },
    isCode: false,
  }),

  computed: {
    nameErrors () {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.maxLength && errors.push('Name must be at most 10 characters long')
      !this.$v.name.required && errors.push('Name is required.')
      return errors
    },
    emailErrors () {
      const errors = []
      if (!this.$v.email.$dirty) return errors
      !this.$v.email.email && errors.push('Must be valid e-mail')
      !this.$v.email.required && errors.push('E-mail is required')
      return errors
    },
    passwordErrors () {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      !this.$v.password.required && errors.push('Password is required')
      return errors
    },
    checkField() {
      return isNaN(this.email) === false ? true : false
    },
    ...mapGetters([
      'user',
      'isLoggedIn'
    ])
  },

  methods: {
    successAuth(value) {
        console.log('social auth value', value)
        this.loading = true
        if(location.host.startsWith('localhost')) {
          this.loading = false
          this.$router.push('/')
        } else { 
          this.loading = false
          window.location.href = `https://${value.user.company.user_main_domain}?user_id=${value.user.id}`
        }
    },
    socialError(value) {
      console.log('value value', value)
      this.loading = false
      this.phonePlaceholder = true
      this.fieldErrors.phoneFlag = false
      this.fieldErrors.emailFlag = true
      this.fieldErrors.emailText = value.message.email !== undefined ? value.message.email : 'Нет такого пользователя'
      this.fieldErrors.labelFlag = false
      this.errored = true
      console.log(this.fieldErrors.emailText)
    },
    async sendCode() {
      const code = `${this.code.first}${this.code.second}${this.code.third}${this.code.four}`
      const phone = localStorage.getItem('phone') ? localStorage.getItem('phone') : '' 
      const response = await this.$store.dispatch('userVerify', { verification_token: code, phone, is_reset_password: true })
      if(response.access_token) {
        // this.$store.dispatch('login', { phone: response.user.phone })
        // this.$router.push('/login')
        localStorage.setItem('token', JSON.stringify(response.access_token))
        window.location.href = `https://${response.user.company.user_main_domain}?user_id=${response.user.id}`
      } else {
        this.isCode = true
      }
      console.log('code response', response)
    },
    checkFieldHandler(position) {
      console.log('position', position)
      this.isCode = false
      switch(position) {
        case 1:
          if(this.code.first.length) this.$refs.secondCodeField.focus()
          break
        case 2:
          if(this.code.second.length) this.$refs.thirdCodeField.focus()
          break
        case 3:
          if(this.code.third.length) this.$refs.fourCodeField.focus()
          break
        case 4:
          if(this.code.four.length) this.$refs.fourCodeField.focus()
          break
        default:
          this.$refs.firstCodeField.focus()
      }
    },
    checkRegisterValue() {
      if( !isNaN(this.email) && this.email.length ) {
        this.phonePlaceholder = true
        this.fieldErrors.phoneFlag = false
        this.fieldErrors.phoneText = null
        this.fieldErrors.labelFlag = true
      } else {
        this.phonePlaceholder = false
        this.fieldErrors.emailFlag = false
        this.fieldErrors.emailText = null
        this.fieldErrors.labelFlag = true
      }
    },
    clear() { 
      this.email = ''
      this.fieldErrors.emailFlag = false
      this.fieldErrors.phoneFlag = false
      this.fieldErrors.phoneText = ""
      this.fieldErrors.emailText = ""
      this.fieldErrors.labelFlag = true
    },
    focusInput() {
      //alert("alert")
      this.$refs.password.focus();
    },
    // checkRegisterValue() {
    //   if( !isNaN(this.email) && this.email.length ) {
    //     this.phonePlaceholder = true
    //   } else {
    //     this.phonePlaceholder = false
    //   }
    // },
    showPassword() {
      if(this.type === 'password') {
        this.type = 'text'
        this.showPass = true
      } else {
        this.type = 'password'
        this.showPass = false
      }
    },
    reminderSwitch() {
      this.loginForm = false
      this.reminderForm =  true
    },
    goEnter() {
      this.enterCode = true
    },
    goBack() {
      this.loginForm = true
      this.reminderForm = false
      this.reminderSent = false
    },
    reminderSubmit() {
      let currentLocale = localStorage.getItem('locale') ? localStorage.getItem('locale') : 'ru'
      let self = this
      console.log('currentLocale', currentLocale)
      if(!isNaN(this.email) && this.email.length > 0) {
        this.phonePlaceholder = true
        let data = { phone: this.email, lang: currentLocale }
        try {
          this.loading = true
          this.$store.dispatch('resendAction', data).then(({ data }) => { 
            console.log('datas', data)
            if(data.message) {
              console.log('data message phone', data.message)
              self.fieldErrors.emailFlag = false
              self.fieldErrors.phoneFlag = true
              self.fieldErrors.phoneText = [data.message]
              self.fieldErrors.labelFlag = false
              self.loading = false
              return
            } else {
              console.log('Phone registration')
              self.fieldErrors.phoneFlag = false
              self.fieldErrors.phoneText = null
              self.loading = false
              localStorage.setItem('phone', this.email)
              self.enterCode = true
              return
            }
          })
        } catch (e) {
          this.loading = false
          console.log(e)
        }
      } else {
        let data = { email: this.email, is_reset_password: true, lang: currentLocale }
        console.log('email data', data)
        if (this.$v.email.$invalid) {
          this.$v.email.$touch()
          return
        } else {
          console.log('else email')
          try {
            this.loading = true
            this.$store.dispatch('resendAction', data).then(({ data }) => { 
              console.log('datas email', data)
              if(data.message) {
                console.log('data message email')
                this.fieldErrors.phoneFlag = false
                this.fieldErrors.emailFlag = true
                this.fieldErrors.emailText = 'Такого пользователя нет'
                this.fieldErrors.labelFlag = false
                this.loading = false
              } else {
                this.fieldErrors.emailFlag = false
                this.fieldErrors.emailText = null
                localStorage.setItem('email', this.email)
                this.loading = false
                // this.nextStage()
                this.reminderSent = true
              }
            })
          } catch (e) {
            this.loading = false
            console.log('sffsfsfsf', e)
          }
        }
      }
    },
    resend(type) {
      let storageFieldPhone = localStorage.getItem('phone') ? localStorage.getItem('phone') : ''
      let storageFieldEmail = localStorage.getItem('email') ? localStorage.getItem('email') : ''
      let self = this
      console.log('storageField', storageFieldPhone, storageFieldEmail)
      console.log(type)
      if(type === 'email') {
        self.$store.dispatch('resendAction', { email: storageFieldEmail }).then(data => {
          console.log('data', data)
          if(data.data.success) {
            self.resendState = true
            setTimeout(() => self.resendState = false, 30000)
          }
        })
      } else if(type === 'phone') {
        self.$store.dispatch('resendAction', { phone: storageFieldPhone }).then(data => {
          console.log('data', data)
          if(data.data.success) {
            self.resendState = true
            setTimeout(() => self.resendState = false, 30000)
          }
        })
      } else {
        return false
      }
    },
    endTimer() {
      this.resendState = false
    },
    async login() {
      console.log('start login', PROTOCOL)
      console.log('window', window.location.hostname)
      if(!isNaN(this.email)) {
        this.loading = true
        const formData = {
          phone: this.email,
          password: this.password
        }
        try {
          console.log("start")
          await this.$store.dispatch('login', formData)
          this.loading = false
          if(location.host.startsWith('localhost')) {
            await this.$router.push('/')
          } else {
            window.location.href = `https://${JSON.parse(localStorage.getItem('user')).company.user_main_domain}?user_id=${JSON.parse(localStorage.getItem('user')).id}`
          }
        } catch (e) {
          console.log(e)
          this.loading = false
          this.errored = true
        }
      } else {
        this.$v.$touch()
        if (this.$v.email.$invalid || this.$v.password.$invalid) {
          this.$v.email.$touch()
          this.$v.password.$touch()
          return
        } else {
          this.loading = true
          const formData = {
            email: this.email,
            password: this.password
          }
          try {
            console.log("start")
            await this.$store.dispatch('login', formData)
            this.loading = false
            if(location.host.startsWith('localhost')) {
              await this.$router.push('/')
            } else {
              window.location.href = `https://${JSON.parse(localStorage.getItem('user')).company.user_main_domain}?user_id=${JSON.parse(localStorage.getItem('user')).id}`
            }
          } catch (e) {
            console.log(e)
            this.loading = false
            this.errored = true
          }
        }
      }

    }
  },
  beforeCreate() {
    console.log('before create')
  },
  async created() {
    // Проверка на logout
    localStorage.clear()

    console.log('search', location.search)
    if(location.search === "?logout") localStorage.clear()
  
    // Проверка на логин по почте
    if(location.search) {
      console.log('created href', location.search)

      const locationSearch = location.search !== "" ? location.search.split('&') : "" 
      const locationToken = locationSearch[0].split('=')[1]
      const locationCheckType = locationSearch[1].split('=')[0]
      const locationEmail = locationCheckType === 'email' ? locationSearch[1].split('=')[1].replace('%', '').replace('40', '@') : +locationSearch[1].split('=')[1]
      console.log('created href', location.search, locationSearch, locationToken, locationEmail)
      console.log('locationCheckType', locationCheckType)
      const resultObject = {
        verification_token: locationToken,
        [locationCheckType]: locationEmail
      }

      // if(locationCheckType !== 'email') localStorage.clear()

      console.log('resultObject', resultObject)

      if(locationToken && locationEmail) {
        const response = await this.$store.dispatch('userVerify', { ...resultObject })
        console.log('response', response)
        localStorage.setItem('token', JSON.stringify(response.access_token))
        localStorage.setItem('user_id', response.user.id)
        localStorage.setItem('user_main_domain', response.user.company.user_main_domain)
        window.location.href=`https://${response.user.company.user_main_domain}?user_id=${response.user.id}`
      }
    }

    // if( window.location.pathname.replace('/','') === 'login' && localStorage.getItem('token') !== null) {
    //   window.location.href = '/'
    // }
    // const { 
    //   data: { 
    //     data: { 
    //       user,
    //       access_token
    //     } 
    //   } 
    // } = await axios.get(`https://gateway.esl.in.ua/api/v1/user/checkSession`)
    // console.log('Data data data', user, access_token, this.isLoggedIn, this.$router)
    // if( access_token.length ) {
    //   await localStorage.setItem('token', JSON.stringify(access_token))
    //   await this.$store.dispatch('changeUserData', user)
    //   window.location.href = '/'
    // }
  },
  mounted() {
    console.log('mounted', this.$router)
    setTimeout(() => {
      this.animateTrigger = true
    },1000)
    setTimeout(() => {
      this.svgScale = true
    },2000)
    setTimeout(() => {
      this.anim1 = true
      this.anim2 = true
    },100)
    this.animateTrigger2 = true
  }

}
</script>

<style lang="sass">
.clear
  opacity: 0 
  pointer-events: none
  transition: opacity .3s ease-in-out
  &.show
    pointer-events: visible
    opacity: 1

.countdown-wrapper
  .countdown-label
      font-size: 18px
      line-height: 1
      font-weight: 300
      margin-bottom: 20px
      color: #fff
  .countdown
      display: flex
      align-items: center
      justify-content: center
      &-item
          color: #fff
          &:not(:last-child)
              margin-right: 10px  
          .digit
              font-size: 24px
              font-weight: 500
              margin-right: 5px
          .text
              font-size: 18px
              opacity: .5

</style>